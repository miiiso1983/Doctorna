-- Add user phone and doctor WhatsApp config
ALTER TABLE users ADD COLUMN phone VARCHAR(32) NULL AFTER email;
ALTER TABLE doctors ADD COLUMN whatsapp_from_phone_id VARCHAR(64) NULL AFTER working_hours;
ALTER TABLE doctors ADD COLUMN whatsapp_enabled TINYINT(1) NOT NULL DEFAULT 0 AFTER whatsapp_from_phone_id;

