import { DataTypes, Model } from 'sequelize';
import { sequelize } from '../config/database';

class Employee extends Model {
  public id!: number;
  public name!: string;
  public position!: string;
  public photo!: string | null;
}

Employee.init(
  {
    id: {
      type: DataTypes.INTEGER.UNSIGNED,
      autoIncrement: true,
      primaryKey: true,
    },
    name: {
      type: new DataTypes.STRING(128),
      allowNull: false,
    },
    email: {
      type: new DataTypes.STRING(128),
      allowNull: false,
    },
    position: {
      type: new DataTypes.STRING(128),
      allowNull: false,
    },
    photo: {
      type: new DataTypes.STRING(255),
      allowNull: true,
    },
  },
  {
    sequelize,
    modelName: 'employee',
    timestamps: true,
    createdAt: 'created_at',
    updatedAt: 'updated_at' 
  }
);

export default Employee;
