create database btp;
\c btp;

DROP VIEW v_finition_devis;
DROP VIEW v_travaux_devis;
DROP VIEW v_devis;
DROP VIEW v_total_payement;
DROP VIEW v_maison;
DROP VIEW v_travaux_maison;
DROP VIEW v_travaux;
DROP TABLE detail_travaux_maison;
DROP TABLE travaux_maison;
DROP TABLE finition_devis;
DROP TABLE finition;
DROP TABLE maison;
DROP TABLE travaux;
DROP TABLE unite;
DROP TABLE travaux_devis;
DROP TABLE devis;

CREATE TABLE client (
  id SERIAL PRIMARY KEY,
  contact VARCHAR(20) UNIQUE
);

CREATE TABLE travaux (
  id SERIAL PRIMARY KEY,
  code VARCHAR(3) UNIQUE NOT NULL,
  travaux VARCHAR(50) UNIQUE NOT NULL,
  unite VARCHAR(20) NOT NULL,
  pu FLOAT NOT NULL
);

CREATE TABLE maison (
  id SERIAL PRIMARY KEY,
  type_maison TEXT UNIQUE NOT NULL,
  descri TEXT NOT NULL,
  surface FLOAT,
  duree_travaux FLOAT
);

CREATE TABLE travaux_maison (
  id SERIAL PRIMARY KEY,
  id_maison INT,
  id_travaux INT,
  qte FLOAT,
  FOREIGN KEY (id_maison) REFERENCES maison(id),
  FOREIGN KEY (id_travaux) REFERENCES travaux(id)
);


CREATE OR REPLACE VIEW v_travaux_maison as SELECT tm.id_maison,m.type_maison,m.descri,m.surface,m.duree_travaux,t.id as id_travaux,t.code,t.travaux,t.unite,t.pu,tm.qte FROM travaux_maison tm
  JOIN maison m on tm.id_maison=m.id
  JOIN travaux t on tm.id_travaux=t.id;

CREATE OR REPLACE VIEW v_devis_travaux_maison as SELECT t.ref_devis,tm.id_maison,m.type_maison,m.descri,m.surface,m.duree_travaux,t.id_travaux,t.code,t.travaux,t.unite,t.pu,tm.qte FROM travaux_maison tm
  JOIN maison m on tm.id_maison=m.id
  JOIN v_travaux_devis t on tm.id_travaux=t.id_travaux;

CREATE OR REPLACE VIEW v_maison as SELECT id_maison,type_maison,descri,surface,duree_travaux,sum(qte*pu) as prix FROM v_travaux_maison GROUP BY id_maison,type_maison,descri,surface,duree_travaux;

CREATE OR REPLACE VIEW v_devis_maison as SELECT ref_devis,id_maison,type_maison,descri,surface,duree_travaux,sum(qte*pu) as prix FROM vvv GROUP BY ref_devis,id_maison,type_maison,descri,surface,duree_travaux;

CREATE TABLE finition (
  id SERIAL PRIMARY KEY,
  finition VARCHAR(200) UNIQUE,
  taux_finition FLOAT NOT NULL
);

CREATE TABLE devis (
  id SERIAL PRIMARY KEY,
  ref_devis VARCHAR(20) UNIQUE,
  date_devis DATE NOT NULL,
  date_debut DATE NOT NULL,
  lieu VARCHAR(100),
  id_client INT,
  id_maison INT,
  id_finition INT,
  FOREIGN KEY (id_client) REFERENCES client(id),
  FOREIGN KEY (id_maison) REFERENCES maison(id),
  FOREIGN KEY (id_finition) REFERENCES finition(id)
);

CREATE TABLE paiement (
  id SERIAL PRIMARY KEY,
  ref_paiement VARCHAR(20) UNIQUE,
  ref_devis VARCHAR(20),
  date_paiement date,
  montant FLOAT,
  FOREIGN KEY (ref_devis) REFERENCES devis(ref_devis)
);

CREATE OR REPLACE VIEW v_total_paiement as SELECT ref_devis,sum(montant) as paye FROM paiement group by ref_devis;

CREATE OR REPLACE VIEW v_total_tout_paiement as SELECT sum(montant) as paye FROM paiement;

CREATE OR REPLACE VIEW v_devis as SELECT  d.id,d.date_devis,d.date_debut,(date_debut + (m.duree_travaux || ' days')::interval)::date AS date_fin,((m.prix*(100+f.taux_finition))/100) as prix_total,COALESCE(p.paye, 0) as paye,((m.prix*(100+f.taux_finition))/100)-COALESCE(p.paye, 0) as reste,d.id_client,u.contact,m.*,d.id_finition,f.finition,f.taux_finition FROM devis d
  JOIN v_devis_maison m on d.ref_devis=m.ref_devis
  JOIN client u on u.id=d.id_client
  JOIN v_finition_devis f on f.ref_devis=d.ref_devis
  LEFT JOIN v_total_paiement p on p.ref_devis=d.ref_devis;

CREATE OR REPLACE VIEW v_montant_total_devis as select sum(prix_total) as montant_total FROM v_devis; 


CREATE TABLE travaux_devis (
  id SERIAL PRIMARY KEY,
  code_travaux VARCHAR(20),
  ref_devis VARCHAR(20),
  pu FLOAT,
  qte FLOAT,
  FOREIGN KEY (ref_devis) REFERENCES devis(ref_devis),
  FOREIGN KEY (code_travaux) REFERENCES travaux(code)
);

CREATE OR REPLACE VIEW v_travaux_devis as SELECT td.ref_devis,t.id as id_travaux,t.code,t.travaux,t.unite,td.pu,td.qte FROM travaux_devis td JOIN travaux t on t.code=td.code_travaux;

CREATe or replace view vv as select vtd.*,d.id_maison FROM v_travaux_devis vtd join devis d on vtd.ref_devis=d.ref_devis;

create or replace view vvv as select v.*,m.type_maison,m.descri,m.surface,m.duree_travaux from vv v join maison m on m.id=v.id_maison; 

CREATE TABLE finition_devis (
  id SERIAL PRIMARY KEY,
  id_finition INT,
  ref_devis VARCHAR(20),
  taux_finition FLOAT,
  FOREIGN KEY (ref_devis) REFERENCES devis(ref_devis),
  FOREIGN KEY (id_finition) REFERENCES finition(id)
);

CREATE OR REPLACE VIEW v_finition_devis as SELECT fd.ref_devis,fd.id_finition,f.finition,fd.taux_finition FROM finition_devis fd JOIN finition f on f.id=fd.id_finition; 

CREATE TABLE import (
  id SERIAL PRIMARY KEY,
  type_maison VARCHAR(100),
  descri VARCHAR(100),
  surface FLOAT,
  code_travaux VARCHAR(100),
  travaux VARCHAR(100),
  unite VARCHAR(100),
  pu FLOAT,
  qte FLOAT,
  duree_travaux FLOAT
);

CREATE TABLE importdevis (
  id SERIAL PRIMARY KEY,
  client VARCHAR(100),
  ref_devis VARCHAR(100),
  type_maison VARCHAR(100),
  finition VARCHAR(100),
  taux_finition FLOAT,
  date_devis DATE,
  date_debut DATE,
  lieu VARCHAR(100)
);

CREATE TABLE importpaiement (
  id SERIAL PRIMARY KEY,
  ref_devis VARCHAR(100),
  ref_paiement VARCHAR(100),
  date_paiement DATE,
  montant FLOAT
);
