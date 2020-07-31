DROP DATABASE IF EXISTS CareerPortal;
CREATE DATABASE CareerPortal;
USE CareerPortal;

CREATE TABLE EmployeeCategory
(
    EmployeeCategoryId INT NOT NULL AUTO_INCREMENT,
    Status VARCHAR(100) NOT NULL,
    MonthlyCharge DECIMAL NOT NULL,
    MaxJobs VARCHAR(100),
    PRIMARY KEY (EmployeeCategoryId)
);

CREATE TABLE EmployerCategory
(
    EmployerCategoryId INT NOT NULL AUTO_INCREMENT,
    Status VARCHAR(100) NOT NULL,
    MonthlyCharge DECIMAL NOT NULL,
    MaxJobs VARCHAR(100),
    PRIMARY KEY (EmployerCategoryId)
);

CREATE TABLE Employer
(
	EmployerId INT NOT NULL AUTO_INCREMENT,
    UserName VARCHAR(100) UNIQUE NOT NULL,
    UserPassword VARCHAR(100) NOT NULL,
    Email VARCHAR(100),
    Company VARCHAR(100) UNIQUE NOT NULL,
    Telephone VARCHAR(14) NOT NULL,
    PostalCode VARCHAR(6) NOT NULL,
    City VARCHAR(100) NOT NULL,
    Address VARCHAR(100) NOT NULL,
    EmployerCategoryId INT NOT NULL,
    PRIMARY KEY (EmployerId),
    FOREIGN KEY (EmployerCategoryId) REFERENCES EmployerCategory (EmployerCategoryId)
);

CREATE TABLE Job
(
    JobId INT NOT NULL AUTO_INCREMENT,
    Title VARCHAR(100) NOT NULL,
    Category SMALLINT NOT NULL,
    JobDescription VARCHAR(250) NOT NULL,
    DatePosted DATE NOT NULL,
    NeededEmployees INT NOT NULL,
    AppliedEmployees INT NOT NULL,
    AcceptedOffers INT NOT NULL,
    EmployerId INT NOT NULL, 
    PRIMARY KEY (JobId),
    FOREIGN KEY (EmployerId) REFERENCES Employer (EmployerId)
);

CREATE TABLE Employee
(
	EmployeeId INT NOT NULL AUTO_INCREMENT,
    UserName VARCHAR(100) UNIQUE NOT NULL,
    UserPassword VARCHAR(100) NOT NULL,
    Email VARCHAR(100) NOT NULL,
    Telephone VARCHAR(14) NOT NULL,
    PostalCode VARCHAR(6) NOT NULL,
    City VARCHAR(100) NOT NULL,
    Address VARCHAR(100) NOT NULL,
    EmployeeCategoryId INT NOT NULL,
    PRIMARY KEY (EmployeeId),
    FOREIGN KEY (EmployeeCategoryId) REFERENCES EmployeeCategory (EmployeeCategoryId)
);

CREATE TABLE JobApplication
(
    EmployeeId INT NOT NULL,
    JobId INT NOT NULL,
    Status SMALLINT NOT NULL,
    PRIMARY KEY (EmployeeId, JobId),
    FOREIGN KEY (EmployeeId) REFERENCES Employee (EmployeeId),
    FOREIGN KEY (JobId) REFERENCES Job (JobId)
);

CREATE TABLE JobOffer
(
    EmployeeId INT NOT NULL,
    JobId INT NOT NULL,
    Status SMALLINT NOT NULL,
    CreationDate date NOT NULL,
    PRIMARY KEY (EmployeeId, JobId),
    FOREIGN KEY (EmployeeId) REFERENCES Employee (EmployeeId),
    FOREIGN KEY (JobId) REFERENCES Job (JobId)
);
