-- Seed data for Tabeebna
INSERT INTO users (name, email, password, role) VALUES
('Super Admin', 'admin@doctorna.local', '$2y$12$vb3zk08ON1yPUtwdXapGGeHiLwZp7ym2zumA1vT5vEgW3KRam8ILK', 'super_admin');
-- Password for admin is: admin123

-- Demo doctor and patient (password for both is: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Dr Ahmed', 'doctor@doctorna.local', '$2y$12$vb3zk08ON1yPUtwdXapGGeHiLwZp7ym2zumA1vT5vEgW3KRam8ILK', 'doctor'),
('Ali Patient', 'patient@doctorna.local', '$2y$12$vb3zk08ON1yPUtwdXapGGeHiLwZp7ym2zumA1vT5vEgW3KRam8ILK', 'patient');

-- Create corresponding doctor and patient profiles
INSERT INTO doctors (user_id, specialization_id, bio) VALUES
((SELECT id FROM users WHERE email='doctor@doctorna.local'), 1, 'General practitioner');

INSERT INTO patients (user_id, date_of_birth, gender) VALUES
((SELECT id FROM users WHERE email='patient@doctorna.local'), '1990-01-01', 'male');


INSERT INTO specializations (name) VALUES
('General Practitioner'),
('Cardiology'),
('Dermatology'),
('Neurology'),
('Pediatrics'),
('Orthopedics');

