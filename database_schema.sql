-- Main User Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL, -- Store hashed passwords, not plain text
    email VARCHAR(150) NOT NULL UNIQUE,
    role ENUM('Admin', 'Worker') NOT NULL DEFAULT 'Worker',
    full_name VARCHAR(150),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Farm Settings Table (could be a key-value store or specific columns)
-- Using specific columns for this example as it's simpler for the current scope
CREATE TABLE farm_settings (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Could be just one row
    farm_name VARCHAR(255) DEFAULT 'My Farm',
    measurement_units ENUM('imperial', 'metric') DEFAULT 'imperial', -- lbs/acres vs kg/hectares
    default_currency VARCHAR(10) DEFAULT 'USD', -- e.g., USD, EUR
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Locations for Inventory (e.g., Barn A, Field 3, Silo 2)
CREATE TABLE locations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Main Inventory Items Table
CREATE TABLE inventory_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_name VARCHAR(255) NOT NULL,
    item_type ENUM('Animal', 'Crop', 'Equipment', 'Supplies') NOT NULL,
    quantity DECIMAL(10, 2) NOT NULL DEFAULT 0.00, -- Using DECIMAL for flexibility (e.g., 10 head, 25.5 kg)
    unit_of_measure VARCHAR(50), -- e.g., 'head', 'kg', 'liters', 'units', 'bags'
    location_id INT, -- Foreign key to locations table
    date_acquired DATE,
    purchase_price DECIMAL(10, 2),
    supplier VARCHAR(255),
    
    -- Animal Specific Fields (NULLable if not an animal)
    health_status VARCHAR(100), -- e.g., Healthy, Sick, Under Treatment
    breed VARCHAR(100), -- For animals

    -- Crop Specific Fields (NULLable if not a crop)
    growth_stage VARCHAR(100), -- e.g., Seedling, Vegetative, Harvested
    planting_date DATE, -- For crops
    expected_harvest_date DATE, -- For crops
    
    -- Equipment Specific Fields (NULLable if not equipment)
    serial_number VARCHAR(255),
    model VARCHAR(255),
    purchase_date DATE,
    warranty_expires_date DATE,

    notes TEXT,
    reorder_level DECIMAL(10,2) DEFAULT 0.00,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (location_id) REFERENCES locations(id) ON DELETE SET NULL -- Allow item to exist if location is deleted
);

-- Equipment Maintenance Logs
CREATE TABLE maintenance_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    equipment_id INT NOT NULL,
    service_date DATE NOT NULL,
    service_type VARCHAR(255) NOT NULL,
    operating_hours_at_service DECIMAL(10,1), -- e.g., 150.5 hours
    cost DECIMAL(10, 2),
    performed_by VARCHAR(255),
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (equipment_id) REFERENCES inventory_items(id) ON DELETE CASCADE -- If equipment is deleted, its logs are deleted
);

-- Item Transactions (for tracking stock changes)
CREATE TABLE item_transactions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    item_id INT NOT NULL,
    transaction_date DATETIME NOT NULL,
    transaction_type ENUM('Purchase', 'Usage', 'Sale', 'Adjustment_In', 'Adjustment_Out', 'Spoilage', 'Birth', 'Death', 'Harvest') NOT NULL,
    quantity_change DECIMAL(10, 2) NOT NULL, -- Positive for additions, negative for subtractions
    related_to_reference VARCHAR(255), -- e.g., Order ID, Batch No, Event
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (item_id) REFERENCES inventory_items(id) ON DELETE CASCADE -- If item is deleted, its transactions are deleted
);

-- Insert a default location if needed, as inventory_items might reference it
-- INSERT INTO locations (name, description) VALUES ('Uncategorized', 'Default location for items not yet assigned');

-- Insert default farm settings (only one row usually)
-- INSERT INTO farm_settings (farm_name, measurement_units) VALUES ('Sunny Acres Farm', 'imperial');

-- Example of adding a first user (password should be hashed in real app)
-- INSERT INTO users (username, password_hash, email, role, full_name) VALUES ('admin', 'your_hashed_password_here', 'admin@farm.com', 'Admin', 'Administrator');
