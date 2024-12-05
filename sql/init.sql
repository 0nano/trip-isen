CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    fname VARCHAR(50) NOT NULL,
    lname VARCHAR(50) NOT NULL,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL,
    jwt VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS images (
    id SERIAL PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    proprietor VARCHAR(50)
);

INSERT INTO users (fname, lname, username, email, password, jwt) VALUES ('admin', 'admin', 'admin', 'admin@admin.admin', '$2a$12$vhBygz7Kk8Xc4xq5L2coPuoipCkmvABuD0Ap/uldjIaunYZlsN.Qi', 'ewogICJhbGciOiAibm9uZSIsCiAgInR5cCI6ICJKV1QiCn0=.eyJ1c2VyIjoiYWRtaW4ifQ.xLtLdUxXsGB7EqP49a8xQziqpjkVKeJ9o2nix4xLf5M');
INSERT INTO images (name, proprietor) VALUES ('admin.jpg', 'admin');