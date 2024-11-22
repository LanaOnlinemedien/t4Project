-- Create table users
CREATE TABLE users (
                       user_id INT AUTO_INCREMENT PRIMARY KEY,
                       username VARCHAR(255) NOT NULL,
                       password VARCHAR(255) NOT NULL
);

-- Create table books
CREATE TABLE books (
                       book_id INT AUTO_INCREMENT PRIMARY KEY,
                       user_id INT NOT NULL,
                       cover VARCHAR(255),
                       title VARCHAR(255) NOT NULL,
                       author VARCHAR(255),
                       rating INT,
                       genre VARCHAR(255),
                       annotation TEXT,
                       FOREIGN KEY (user_id) REFERENCES users(user_id)
);

