-- Create the database
CREATE DATABASE IF NOT EXISTS expenseTracker;

-- Use the database
USE expenseTracker;

-- Create the usertable for user authentication
CREATE TABLE IF NOT EXISTS usertable (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    code INT NOT NULL,
    status ENUM('verified', 'notverified') NOT NULL
);

-- Create the income table for tracking income
CREATE TABLE IF NOT EXISTS income (
    email VARCHAR(255) NOT NULL,
    id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    value DECIMAL(10, 2) NOT NULL,
    category VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    PRIMARY KEY (email, id),
    FOREIGN KEY (email) REFERENCES usertable(email) ON DELETE CASCADE
);

-- Create the expense table for tracking expenses
CREATE TABLE IF NOT EXISTS expense (
    email VARCHAR(255) NOT NULL,
    id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    value DECIMAL(10, 2) NOT NULL,
    category VARCHAR(255) NOT NULL,
    date DATE NOT NULL,
    PRIMARY KEY (email, id),
    FOREIGN KEY (email) REFERENCES usertable(email) ON DELETE CASCADE
);

-- Create the budget table for tracking budgets and savings
CREATE TABLE IF NOT EXISTS budget (
    id INT NOT NULL,
    email VARCHAR(255) NOT NULL,
    budget DECIMAL(10, 2) NOT NULL,
    savings DECIMAL(10, 2) NOT NULL,
    date DATE NOT NULL,
    PRIMARY KEY (id, email),
    FOREIGN KEY (email) REFERENCES usertable(email) ON DELETE CASCADE
);
