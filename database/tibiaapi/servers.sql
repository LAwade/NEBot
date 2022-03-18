INSERT INTO tibia_api_server (name,host, active) VALUES('LOCALHOST','127.0.0.1', 1);

INSERT INTO tibia_api (server, src, url, premium, fk_id_tibia_api_server, active) VALUES ('Kaldrox', 'versao86','https://www.kaldrox.com',0,1,1);
INSERT INTO tibia_api (server, src, url, premium, fk_id_tibia_api_server, active) VALUES ('Underwar', 'underwar','https://www.underwar.org',0,1,1);
INSERT INTO tibia_api (server, src, url, premium, fk_id_tibia_api_server, active) VALUES ('Epicwar', 'epicwar','https://www.epicwarserver.com',0,1,1); 