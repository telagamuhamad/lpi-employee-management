<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\User;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\HTTP\Response;

class UserController extends BaseController
{
    protected $userModel;
    protected $apiBaseUrl = 'http://localhost:3000/api/users';

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Display list of users
     *
     * @return void
     */
    public function index()
    {
        $ch = curl_init($this->apiBaseUrl . '/getUsers');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        if ($response === false) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        $users = json_decode($response, true);

        // Check for JSON decode errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'JSON Decode Error: ' . json_last_error_msg();
            echo "<pre>";
            var_dump($response);
            echo "</pre>";
            exit;
        }

        return $this->redirect(
            'user/user_management',
            [
                'users' => $users
            ]
        );
    }

    /**
     * Show the details of a single user
     * 
     * @param int $id
     */
    public function show($id)
    {
        $ch = curl_init("http://localhost:3000/api/users/$id");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        
        if ($response === false) {
            echo 'cURL Error: ' . curl_error($ch);
            curl_close($ch);
            exit;
        }
    
        curl_close($ch);
    
        $user = json_decode($response, true);
    
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo 'JSON Decode Error: ' . json_last_error_msg();
            echo "<pre>";
            var_dump($response);
            echo "</pre>";
            exit;
        }
    
        if (empty($user)) {
            return $this->jsonResponse([
                'message' => 'User not found'
            ], ResponseInterface::HTTP_NOT_FOUND);
        }
    
        return $this->redirect(
            'user/user_edit',
            [
                'user' => $user
            ]
        );
    }
    

    /**
     * Show the form to create a new user
     */
    public function create()
    {
        return $this->redirect(
            'user/user_create',
            [
                
            ]
        );
    }

    /**
     * Store a newly created user
     */
    public function store()
    {
        // Ambil data dari form
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'level' => $this->request->getPost('level')
        ];
    
        // Konversi data ke format JSON
        $jsonData = json_encode($data);
    
        // Inisialisasi cURL
        $ch = curl_init("http://localhost:3000/api/users/create");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonData)
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    
        // Eksekusi cURL dan dapatkan respons
        $response = curl_exec($ch);
    
        // Cek apakah ada error
        if ($response === false) {
            $errorMessage = 'cURL Error: ' . curl_error($ch);
            curl_close($ch);
            return redirect()->back()->with('error', $errorMessage);
        }
    
        curl_close($ch);
    
        // Decode respons dari JSON ke array
        $responseArray = json_decode($response, true);
    
        // Proses respons dari API
        if (isset($responseArray['id'])) {
            return redirect()->to("/users")->with('success', 'User created successfully');
        } else {
            $errorMessage = 'Failed to create user: ' . ($responseArray['error'] ?? 'Unknown error');
            return redirect()->back()->with('error', $errorMessage);
        }
    }
    

    /**
     * Update the specified user
     * 
     * @param int $id
     */
    public function update($id)
    {
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'level' => $this->request->getPost('level')
        ];
    
        if ($this->request->getPost('password')) {
            $data['password'] = $this->request->getPost('password');
        }
    
        $jsonData = json_encode($data);
    
        $ch = curl_init("http://localhost:3000/api/users/{$id}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
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
    
        if (isset($responseArray['message']) && $responseArray['message'] == 'User updated successfully') {
            return redirect()->to("/users")->with('success', 'User updated successfully');
        } else {
            $errorMessage = 'Failed to update user: ' . ($responseArray['error'] ?? 'Unknown error');
            return redirect()->back()->with('error', $errorMessage);
        }
    }
          

    /**
     * Delete the specified user
     * 
     * @param int $id
     */
    public function delete($id)
    {
        $ch = curl_init("http://localhost:3000/api/users/$id");
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
            return redirect()->to("/users")->with('success', 'User deleted successfully');
        } else {
            $errorMessage = 'Failed to delete user: ' . ($responseArray['error'] ?? 'Unknown error');
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
