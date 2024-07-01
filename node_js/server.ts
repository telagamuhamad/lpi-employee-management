// server.ts
import express from 'express';
import bodyParser from 'body-parser';
import cors from 'cors';
import { sequelize } from './src/config/database';
import userRoutes from './src/routes/userRoutes';
import employeeRoutes from './src/routes/employeeRoutes';

const app = express();
const PORT = process.env.PORT || 3000;

// Middleware
app.use(cors());
app.use(bodyParser.json());

// Routes
app.use('/api/users', userRoutes);
app.use('/api/employees', employeeRoutes);

// Test database connection
sequelize.authenticate()
    .then(() => console.log('Database connected...'))
    .catch((err: any) => console.log('Error: ' + err));

// Start server
app.listen(PORT, () => {
    console.log(`Server is running on port ${PORT}`);
});
