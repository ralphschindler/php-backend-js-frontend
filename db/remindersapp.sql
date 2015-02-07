
CREATE TABLE location (
  id int(11) PRIMARY KEY,
  address text,
  longitude decimal(10,7) DEFAULT '0.0000000',
  latitude decimal(10,7) DEFAULT '0.0000000'
);


CREATE TABLE reminder (
  id int(11) PRIMARY KEY,
  text text,
  created_at datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  remind_at datetime DEFAULT NULL,
  completed_at datetime DEFAULT NULL
);


CREATE TABLE reminder_location (
  reminder_id int(11) NOT NULL,
  location_id int(11) NOT NULL,
  radius_in_feet int(128) NOT NULL DEFAULT '500',
  FOREIGN KEY (reminder_id) REFERENCES reminder (id),
  FOREIGN KEY (location_id) REFERENCES location (id)
);


INSERT INTO location (id, address, longitude, latitude) VALUES
  (1,'sunshinephp',-80.2620174,25.8065975);

INSERT INTO reminder (id, text, created_at, remind_at, completed_at) VALUES
  (1,'Dentist Appt.','2015-02-02 16:00:00','2015-02-02 16:00:00',NULL),
  (2,'SunshinePHP Talk','2015-02-08 00:00:00',NULL,NULL);

INSERT INTO reminder_location (reminder_id, location_id, radius_in_feet) VALUES 
  (2,1,500);
