import { Router } from 'express';
import * as EmployeeController from '../controllers/EmployeeController';
import multer from 'multer';

const router = Router();
const upload = multer({ dest: 'uploads/' });

router.get('/', EmployeeController.getEmployees);
router.post('/create', upload.single('photo'), EmployeeController.createEmployee);
router.get('/:id', EmployeeController.getEmployeeById);
router.put('/:id', upload.single('photo'), EmployeeController.updateEmployee);
router.delete('/:id', EmployeeController.deleteEmployee);

export default router;
