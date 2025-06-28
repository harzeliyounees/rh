# README.md

# Employee Management System

This project is an Employee Management System designed to manage employee data, including functionalities for handling leave requests, overtime hours, night hours, financial records, and employee movements, along with user authentication.

## Features

- User authentication (login and registration)
- Employee management (create, edit, list employees)
- Leave request management
- Overtime hours tracking
- Payroll management
- Responsive design

## Technologies Used

- PHP
- MySQL
- HTML/CSS
- JavaScript

## Installation

1. Clone the repository:
   ```
   git clone <repository-url>
   ```

2. Navigate to the project directory:
   ```
   cd employee-management
   ```

3. Install dependencies using Composer:
   ```
   composer install
   ```

4. Set up the database:
   - Import the `database/schema.sql` file into your MySQL database.

5. Configure the database connection in `src/config/database.php`.

## Usage

- Start the local server and access the application through your web browser.
- Follow the on-screen instructions to register and manage employee data.

## Running Tests

To run the unit tests, use the following command:
```
phpunit tests/Unit/EmployeeTest.php
```

## Contributing

Contributions are welcome! Please open an issue or submit a pull request for any improvements or bug fixes.

## License

This project is licensed under the MIT License. See the LICENSE file for details.