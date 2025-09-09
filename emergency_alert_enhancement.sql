-- Emergency Alert Enhancement
-- Add columns to track volunteer responses

ALTER TABLE emergency_alert 
ADD COLUMN IF NOT EXISTS responded_by INT DEFAULT NULL,
ADD COLUMN IF NOT EXISTS responded_at DATETIME DEFAULT NULL,
ADD COLUMN IF NOT EXISTS resolved_by INT DEFAULT NULL,
ADD COLUMN IF NOT EXISTS resolved_at DATETIME DEFAULT NULL;

-- Add foreign key constraints (optional)
-- ALTER TABLE emergency_alert 
-- ADD CONSTRAINT fk_responded_by FOREIGN KEY (responded_by) REFERENCES user(Id),
-- ADD CONSTRAINT fk_resolved_by FOREIGN KEY (resolved_by) REFERENCES user(Id);

-- Add indexes for better performance
CREATE INDEX IF NOT EXISTS idx_alert_status ON emergency_alert(alert_status);
CREATE INDEX IF NOT EXISTS idx_responded_by ON emergency_alert(responded_by);
CREATE INDEX IF NOT EXISTS idx_responded_at ON emergency_alert(responded_at);
