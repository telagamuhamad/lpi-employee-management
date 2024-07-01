<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Employee;
use CodeIgniter\HTTP\ResponseInterface;

class EmployeeController extends BaseController
{
    protected $employeeModel;

    public function __construct()
    {
        $this->employeeModel = new Employee();
    }
    public function index()
    {
        $ch = curl_init('http://localhost:3000/api/employees');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        $employees = json_decode($response, true);

        // Check for JSON decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'JSON Decode Error: ' . json_last_error_msg();
            echo "<pre>";
            var_dump($response);
            echo "</pre>";
            exit;
        }

        return $this->redirect(
            'employee/employee_management',
            [
                'employees' => $employees
            ]
        );
    }
    

    /**
     * Show the form for creating a new resource.
     * 
     * @return Response
     */
    public function create()
    {
        return $this->redirect(
            'employee/employee_create',
            [
                
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     * 
     * @param Request $request
     * @return Response
     */
    public function store()
    {
        helper(['form', 'url']);

        $rules = [
            'name' => 'required',
            'position' => 'required',
            'email' => 'required|valid_email',
            'photo' => 'uploaded[photo]|max_size[photo,300]|mime_in[photo,image/jpg,image/jpeg,image/png]',
        ];
    
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    
        $photo = $this->request->getFile('photo');
    
        if ($photo->isValid() && !$photo->hasMoved()) {
            $newName = $photo->getRandomName();
            $photo->move(ROOTPATH . 'public/uploads', $newName);
            $photoName = $photo->getName();
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to upload photo.');
        }
    
        $data = [
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'position' => $this->request->getPost('position'),
            'photo' => $photoName,
        ];
    
        $jsonData = json_encode($data);
    
        $ch = curl_init('http://localhost:3000/api/employees/create');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    
        $response = curl_exec($ch);
    
        if ($response === false) {
            $errorMessage = 'cURL Error: ' . curl_error($ch);
            curl_close($ch);
            return redirect()->back()->with('error', $errorMessage);
        }
    
        curl_close($ch);
    
        $responseArray = json_decode($response, true);
    
        if (isset($responseArray['id'])) {
            return redirect()->to("/employees")->with('success', 'User created successfully');
        } else {
            $errorMessage = 'Failed to create Employee: ' . ($responseArray['error'] ?? 'Unknown error');
            return redirect()->back()->with('error', $errorMessage);
        }
    }
    
    

    /**
     * Display the specified resource.
     * 
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        $ch = curl_init("http://localhost:3000/api/employees/{$id}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
    
        if ($response === false) {
            echo 'cURL Error: ' . curl_error($ch);
            curl_close($ch);
            exit;
        }
    
        curl_close($ch);
    
        $employee = json_decode($response, true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'JSON Decode Error: ' . json_last_error_msg();
            echo "<pre>";
            var_dump($response);
            echo "</pre>";
            exit;
        }
    
        if (empty($employee)) {
            return $this->jsonResponse([
                'message' => 'Employee not found'
            ], ResponseInterface::HTTP_NOT_FOUND);
        }
    
        return $this->redirect(
            'employee/employee_edit',
            [
                'employee' => $employee
            ]
        );
    }
    

    /**
     * Show the form for editing the specified resource.
     * 
     * @param int $id
     * @return Response
     */
    public function update($id)
    {
        // Validation rules
        $rules = [
            'name' => 'required',
            'position' => 'required',
            'email' => 'required|valid_email',
            'photo' => 'uploaded[photo]|max_size[photo,300]|mime_in[photo,image/jpg,image/jpeg,image/png]',
        ];
    
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    
        // Handle file upload
        $photo = $this->request->getFile('photo');
        $photoName = null;
    
        if ($photo->isValid() && !$photo->hasMoved()) {
            $uploadDir = WRITEPATH . 'uploads/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
    
            $newName = $photo->getRandomName();
            $photo->move($uploadDir, $newName);
            $photoName = $newName;
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to upload photo.');
        }
    
        // Prepare data for API request
        $data = [
            'name' => $this->request->getPost('name'),
            'position' => $this->request->getPost('position'),
            'email' => $this->request->getPost('email'),
            'photo' => $photoName,
        ];
    
        // Initialize cURL session
        $ch = curl_init("http://localhost:3000/api/employees/{$id}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data)),
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
        // Execute cURL session
        $response = curl_exec($ch);
    
        // Check for errors
        if ($response === false) {
            $errorMessage = 'cURL Error: ' . curl_error($ch);
            curl_close($ch);
            return redirect()->back()->with('error', $errorMessage);
        }
    
        curl_close($ch);
    
        // Decode JSON response
        $responseArray = json_decode($response, true);
    
        // Process API response
        if (isset($responseArray['message'])) {
            return redirect()->to("/employees")->with('success', $responseArray['message']);
        } else {
            $errorMessage = 'Failed to update employee: ' . ($responseArray['error'] ?? 'Unknown error');
            return redirect()->back()->with('error', $errorMessage);
        }
    }
    
     

    /**
     * Remove the specified resource from storage.
     * 
     * @param int $id
     * @return Response
     */
    public function delete($id)
    {
        $ch = curl_init("http://localhost:3000/api/employees/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
    
        $response = curl_exec($ch);
    
        if ($response === false) {
            $errorMessage = 'cURL Error: ' . curl_error($ch);
            curl_close($ch);
            return redirect()->back()->with('error', $errorMessage);
        }
    
        curl_close($ch);
    
        $responseArray = json_decode($response, true);
    
        if (isset($responseArray['message'])) {
            return redirect()->to("/employees")->with('success', 'Employee deleted successfully');
        } else {
            $errorMessage = 'Failed to delete employee: ' . ($responseArray['error'] ?? 'Unknown error');
            return redirect()->back()->with('error', $errorMessage);
        }
    }
      

    /**
     * Method untuk mengirim request ke API Node.js
     * 
     * @param string $method
     * @param string $url
     * @param array $data
     * @return array
     */
    private function sendRequest($method, $url, $data = [])
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_POSTFIELDS => http_build_query($data),
            CURLOPT_HTTPHEADER => array(
                "Content-Type: application/x-www-form-urlencoded"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return [
                'status' => 'error',
                'message' => 'cURL Error #:' . $err
            ];
        } else {
            return [
                'status' => 'success',
                'data' => json_decode($response, true)
            ];
        }
    }
}
