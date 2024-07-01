// userRoutes.ts
import { Router } from 'express';
import * as UserController from '../controllers/UserController';

const router = Router();

router.get('/getUsers', UserController.getUsers);
router.post('/create', UserController.createUser);
router.get('/:id', UserController.getUserById);
router.put('/:id', UserController.updateUser);
router.delete('/:id', UserController.deleteUser);

export default router;
