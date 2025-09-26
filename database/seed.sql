-- Seed data for Tabeebna
INSERT INTO users (name, email, password, role) VALUES
('Super Admin', 'admin@doctorna.local', '$2y$12$vb3zk08ON1yPUtwdXapGGeHiLwZp7ym2zumA1vT5vEgW3KRam8ILK', 'super_admin');
-- Password for admin is: admin123

INSERT INTO specializations (name) VALUES
('General Practitioner'),
('Cardiology'),
('Dermatology'),
('Neurology'),
('Pediatrics'),
('Orthopedics');

