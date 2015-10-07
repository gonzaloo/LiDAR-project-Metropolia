CREATE TABLE devices (
 deviceID INT NOT NULL,
 dLocation CHAR(20),
 dPointingTo CHAR(20)
);

ALTER TABLE devices ADD CONSTRAINT PK_devices PRIMARY KEY (deviceID);


CREATE TABLE readings (
 readingID INT NOT NULL,
 deviceID INT NOT NULL,
 date DATE,
 time TIME,
 speed FLOAT(4)
);

ALTER TABLE readings ADD CONSTRAINT PK_readings PRIMARY KEY (readingID,deviceID);


ALTER TABLE readings ADD CONSTRAINT FK_readings_0 FOREIGN KEY (deviceID) REFERENCES devices (deviceID);


