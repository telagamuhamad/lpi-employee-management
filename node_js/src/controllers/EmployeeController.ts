import { Request, Response } from 'express';
import Employee from '../models/Employee';
import fs from 'fs';
import path from 'path';

export const getEmployees = async (req: Request, res: Response): Promise<void> => {
  try {
    const employees = await Employee.findAll();
    res.json(employees);
  } catch (error) {
    const err = error as Error;
    res.status(500).json({ error: err.message });
  }
};

export const createEmployee = async (req: Request, res: Response): Promise<void> => {
  try {
    const { name, position, email } = req.body;
    let photo: string | null = null;

    // Handle file upload if photo exists
    if (req.file) {
      const uploadDir = path.join(__dirname, '../../uploads');
      if (!fs.existsSync(uploadDir)) {
        fs.mkdirSync(uploadDir);
      }

      const filename = req.file.filename;
      photo = filename;
    }

    const newEmployee = await Employee.create({ name, position, email, photo });

    res.status(201).json(newEmployee);
  } catch (error) {
    const err = error as Error;
    res.status(500).json({ error: err.message });
  }
};


export const getEmployeeById = async (req: Request, res: Response): Promise<void> => {
  try {
    const employeeId = req.params.id;
    const employee = await Employee.findByPk(employeeId);

    if (!employee) {
      res.status(404).json({ message: 'Employee not found' });
      return;
    }

    res.json(employee);
  } catch (error) {
    const err = error as Error;
    res.status(500).json({ error: err.message });
  }
};

export const updateEmployee = async (req: Request, res: Response): Promise<void> => {
  try {
    const employeeId = req.params.id;
    const { name, position, email } = req.body;
    let photo: string | null = null;

    // Handle file upload if photo exists
    if (req.file) {
      const uploadDir = path.join(__dirname, '../../uploads');
      if (!fs.existsSync(uploadDir)) {
        fs.mkdirSync(uploadDir);
      }

      const filename = req.file.filename;
      photo = filename;
    }

    await Employee.update({ name, position, email, photo }, { where: { id: employeeId } });

    res.json({ message: 'Employee updated successfully' });
  } catch (error) {
    const err = error as Error;
    res.status(500).json({ error: err.message });
  }
};


export const deleteEmployee = async (req: Request, res: Response): Promise<void> => {
  try {
    const employeeId = req.params.id;

    await Employee.destroy({ where: { id: employeeId } });

    res.json({ message: 'Employee deleted successfully' });
  } catch (error) {
        const err = error as Error;
        res.status(500).json({ error: err.message });
  }
};
