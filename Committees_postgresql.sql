DROP DATABASE resomun;
CREATE DATABASE RESOMUN;
\c resomun;

CREATE TABLE CONFERENCE (
  conf_id SERIAL,
  conf_title VARCHAR(50) UNIQUE,
  PRIMARY KEY (conf_id)
);

CREATE TABLE COMMITTEE (
  commit_id SERIAL,
  commit_title TEXT,
  PRIMARY KEY (commit_id)
);

CREATE TABLE SSCLAUSE (
  ssclause_id SERIAL,
  ssclause_contents TEXT,
  PRIMARY KEY (ssclause_id)
);

CREATE TABLE PARTICIPATED_IN (
  usr_id INT,
  conf_id INT,
  part_id INT,
  commit_id INT,
  PRIMARY KEY (usr_id, conf_id, commit_id)
);

CREATE TABLE DELEGATION (
  del_id SERIAL,
  country VARCHAR(50),
  PRIMARY KEY (del_id)
);

CREATE TABLE SCLAUSE (
  sclause_id SERIAL,
  sclause_contents TEXT,
  PRIMARY KEY (sclause_id)
);

CREATE TABLE USR (
  usr_id SERIAL,
  firstname VARCHAR(42),
  lastname VARCHAR(42),
  age INT,
  email VARCHAR(42) UNIQUE,
  password VARCHAR(42),
  PRIMARY KEY (usr_id)
);

CREATE TABLE PARTICIPANT (
  part_id SERIAL,
  PRIMARY KEY (part_id)
);

CREATE TABLE SIGNED (
  part_id INT,
  reso_id INT,
  signature TEXT,
  PRIMARY KEY (part_id, reso_id)
);

CREATE TABLE RESOLUTION (
  reso_id SERIAL,
  reso_title VARCHAR(250),
  PRIMARY KEY (reso_id)
);

CREATE TABLE HAS_ROLE (
  part_id INT,
  role_id INT,
  PRIMARY KEY (part_id, role_id)
);

CREATE TABLE ROLE_TABLE (
  role_id SERIAL,
  role_title VARCHAR(42),
  PRIMARY KEY (role_id)
);

CREATE TABLE CLAUSE (
  clause_id SERIAL,
  clause_contents TEXT,
  operative BOOL DEFAULT TRUE,
  PRIMARY KEY (clause_id)
);

CREATE TABLE COMMITTEE_OF (
  committee_id INT UNIQUE,
  conference_id INT,
  PRIMARY KEY (committee_id, conference_id)
);

CREATE TABLE RESO_CONTAINS (
  resolution_id INT,
  clause_id INT,
  PRIMARY KEY (resolution_id, clause_id)
);

CREATE TABLE MAIN_SUB (
  main_sub_id INT,
  resolution_id INT,
  commit_id INT,
  PRIMARY KEY (main_sub_id, resolution_id, commit_id)
);

CREATE TABLE CLAUSE_CONTAINS (
  clause_id INT,
  sclause_id INT,
  PRIMARY KEY (clause_id, sclause_id)
);

CREATE TABLE REPRESENTS (
  part_id INT,
  del_id INT,
  PRIMARY KEY (del_id, part_id)
);

CREATE TABLE SCLAUSE_CONTAINS (
  sclause_id INT,
  ssclause_id INT,
  PRIMARY KEY (sclause_id, ssclause_id)
);

CREATE TABLE AMMENDMENT (
  ammend_id SERIAL,
  ammend_type VARCHAR(5),
  ammend_body TEXT,
  reso_id INT,
  ammendment_sub INT,
  PRIMARY KEY (ammend_id)
);

CREATE TABLE SIGNED_AMMENDMENT (
  part_id INT,
  ammend_id INT,
  PRIMARY KEY (ammend_id, part_id)
);


ALTER TABLE PARTICIPATED_IN ADD CONSTRAINT unique_part_id UNIQUE (part_id);
ALTER TABLE SIGNED_AMMENDMENT ADD FOREIGN KEY (part_id) REFERENCES PARTICIPATED_IN (part_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE SIGNED_AMMENDMENT ADD FOREIGN KEY (ammend_id) REFERENCES AMMENDMENT (ammend_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE SCLAUSE_CONTAINS ADD FOREIGN KEY (sclause_id) REFERENCES SCLAUSE (sclause_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE SCLAUSE_CONTAINS ADD FOREIGN KEY (ssclause_id) REFERENCES SSCLAUSE (ssclause_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE AMMENDMENT ADD FOREIGN KEY (reso_id) REFERENCES RESOLUTION (reso_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE REPRESENTS ADD FOREIGN KEY (part_id) REFERENCES PARTICIPANT (part_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE REPRESENTS ADD FOREIGN KEY (del_id) REFERENCES DELEGATION (del_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE CLAUSE_CONTAINS ADD FOREIGN KEY (clause_id) REFERENCES CLAUSE (clause_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE CLAUSE_CONTAINS ADD FOREIGN KEY (sclause_id) REFERENCES SCLAUSE (sclause_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE MAIN_SUB ADD FOREIGN KEY (main_sub_id) REFERENCES PARTICIPANT (part_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE MAIN_SUB ADD FOREIGN KEY (resolution_id) REFERENCES RESOLUTION (reso_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE RESO_CONTAINS ADD FOREIGN KEY (resolution_id) REFERENCES RESOLUTION (reso_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE RESO_CONTAINS ADD FOREIGN KEY (clause_id) REFERENCES CLAUSE (clause_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE PARTICIPATED_IN ADD FOREIGN KEY (commit_id) REFERENCES COMMITTEE (commit_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE PARTICIPATED_IN ADD FOREIGN KEY (part_id) REFERENCES PARTICIPANT (part_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE PARTICIPATED_IN ADD FOREIGN KEY (conf_id) REFERENCES CONFERENCE (conf_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE PARTICIPATED_IN ADD FOREIGN KEY (usr_id) REFERENCES USR (usr_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE SIGNED ADD FOREIGN KEY (reso_id) REFERENCES RESOLUTION (reso_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE SIGNED ADD FOREIGN KEY (part_id) REFERENCES PARTICIPANT (part_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE HAS_ROLE ADD FOREIGN KEY (role_id) REFERENCES ROLE_TABLE (role_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE HAS_ROLE ADD FOREIGN KEY (part_id) REFERENCES PARTICIPANT (part_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE COMMITTEE_OF ADD FOREIGN KEY (committee_id) REFERENCES COMMITTEE (commit_id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE COMMITTEE_OF ADD FOREIGN KEY (conference_id) REFERENCES CONFERENCE (conf_id) ON UPDATE CASCADE ON DELETE CASCADE;

INSERT INTO ROLE_TABLE(role_title) VALUES
('Secretary General'),
('Deputy Secretary General'),
('Under Secretary General'),
('Assistant Secretary General'),
('Chair'),
('Crisis Director'),
('Intercon Director'),
('Admin'),
('Delegate');

INSERT INTO DELEGATION(country) VALUES
('Afghanistan'),
('Albania'),
('Algeria'),
('Andorra'),
('Angola'),
('Antigua and Barbuda'),
('Argentina'),
('Armenia'),
('Australia'),
('Austria'),
('Azerbaijan'),
('Bahamas'),
('Bahrain'),
('Bangladesh'),
('Barbados'),
('Belarus'),
('Belgium'),
('Belize '),
('Benin '),
('Bhutan'),
('Bolivia'),
('Bosnia and Herzegovina'),
('Botswana'),
('Brazil '),
('Brunei Darussalam'),
('Bulgaria'),
('Burkina'),
('Burundi'),
('Cambodia'),
('Cameroon'),
('Canada'),
('Cape Verde'),
('Central African Republic'),
('Chad'),
('Chile'),
('China'),
('Colombia'),
('Comoros'),
('Congo (Republic of the)'),
('Costa Rica'),
('Côte d’Ivoire'),
('Croatia'),
('Cuba'),
('Cyprus'),
('Czech Republic'),
('Democratic People’s Republic of Korea'),
('Democratic Republic of the Congo'),
('Denmark'),
('Djibouti'),
('Dominica'),
('Dominican Republic'),
('Ecuador'),
('Egypt'),
('El Salvador'),
('Equatorial Guinea'),
('Eritrea'),
('Estonia'),
('Ethiopia'),
('Fiji'),
('Finland'),
('France'),
('Gabon'),
('Gambia'),
('Georgia'),
('Germany'),
('Ghana'),
('Greece'),
('Grenada'),
('Guatemala'),
('Guinea'),
('Guinea-Bissau'),
('Guyana'),
('Haiti'),
('Honduras'),
('Hungary'),
('Iceland'),
('India'),
('Indonesia'),
('Iran'),
('Iraq'),
('Ireland'),
('Israel'),
('Italy'),
('Jamaica'),
('Japan'),
('Jordan'),
('Kazakhstan'),
('Kenya'),
('Kiribati'),
('Kuwait'),
('Kyrgyzstan'),
('Lao People’s Democratic Republic'),
('Latvia'),
('Lebanon'),
('Lesotho'),
('Liberia'),
('Libya'),
('Liechtenstein'),
('Lithuania'),
('Luxembourg'),
('Madagascar'),
('Malawi'),
('Malaysia'),
('Maldives'),
('Mali'),
('Malta'),
('Marshall Islands'),
('Mauritania'),
('Mauritius'),
('Mexico'),
('Micronesia (Federated States of)'),
('Monaco'),
('Mongolia'),
('Montenegro'),
('Morocco'),
('Mozambique'),
('Myanmar'),
('Namibia'),
('Nauru '),
('Nepal '),
('Netherlands'),
('New Zealand'),
('Nicaragua '),
('Niger'),
('Nigeria '),
('Norway '),
('Oman '),
('Pakistan'),
('Palau '),
('Panama'),
('Papua New Guinea'),
('Paraguay'),
('Peru'),
('Philippines'),
('Poland'),
('Portugal'),
('Qatar'),
('Republic of Korea'),
('Republic of Moldova'),
('Romania '),
('Russian Federation'),
('Rwanda'),
('Saint Kitts and Nevis'),
('Saint Lucia'),
('Saint Vincent and the Grenadines'),
('Samoa'),
('San Marino'),
('Sao Tome and Principe'),
('Saudi Arabia'),
('Senegal'),
('Serbia'),
('Seychelles'),
('Sierra Leone'),
('Singapore'),
('Slovakia'),
('Slovenia'),
('Solomon Islands'),
('Somalia'),
('South Africa '),
('South Sudan'),
('Spain'),
('Sri Lanka'),
('Sudan '),
('Suriname '),
('Swaziland'),
('Switzerland'),
('Sweden '),
('Syria'),
('Tajikistan '),
('Thailand'),
('The former Yugoslav Republic of Macedonia'),
('Timor-Leste'),
('Togo'),
('Tonga '),
('Trinidad and Tobago '),
('Tunisia'),
('Turkey '),
('Turkmenistan'),
('Tuvalu'),
('Uganda'),
('Ukraine '),
('United Arab Emirates '),
('United Kingdom '),
('United of Republic of Tanzania'),
('United States'),
('Uruguay'),
('Uzbekistan'),
('Vanuatu'),
('Venezuela'),
('Viet Nam '),
('Yemen'),
('Zambia'),
('Zimbabwe');

