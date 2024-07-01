import { Sequelize } from 'sequelize';

const sequelize = new Sequelize('lpi-employee', 'root', '', {
    host: 'localhost',
    dialect: 'mysql',
    logging: false, // Set to true to see SQL queries
});

sequelize.authenticate()
    .then(() => {
        console.log('Connection has been established successfully.');
    })
    .catch(err => {
        console.error('Unable to connect to the database:', err);
    });

export { sequelize };
