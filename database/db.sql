
ALTER TABLE tsbot_move ADD CONSTRAINT fk_id_bot FOREIGN KEY (fk_id_bot)
      REFERENCES bot(id) ON DELETE CASCADE;

ALTER TABLE tibia_api ADD CONSTRAINT fk_id_tibia_api_server FOREIGN KEY (fk_id_tibia_api_server)
    REFERENCES tibia_api_server(id);

ALTER TABLE tibia ADD CONSTRAINT fk_id_bot FOREIGN KEY (fk_id_bot)
      REFERENCES bot(id) ON DELETE CASCADE;

ALTER TABLE tibia ADD CONSTRAINT fk_id_tibia_api FOREIGN KEY(fk_id_tibia_api) 
	REFERENCES tibia_api (id) ON DELETE CASCADE;

ALTER TABLE tibia_channel ADD CONSTRAINT fk_id_tibia FOREIGN KEY(fk_id_tibia) 
	REFERENCES tibia(id) ON DELETE CASCADE;

ALTER TABLE tibia_friend_list ADD CONSTRAINT fk_id_tibia FOREIGN KEY(fk_id_tibia) 
	REFERENCES tibia (id) ON DELETE CASCADE;

ALTER TABLE tibia_hunted_list ADD CONSTRAINT fk_id_tibia FOREIGN KEY (fk_id_tibia)
      REFERENCES tibia(id) ON DELETE CASCADE;

ALTER TABLE tibia_ally_list ADD CONSTRAINT fk_id_tibia FOREIGN KEY (fk_id_tibia)
      REFERENCES tibia(id) ON DELETE CASCADE;

ALTER TABLE tibia_enemy_list ADD CONSTRAINT fk_id_tibia FOREIGN KEY (fk_id_tibia)
      REFERENCES tibia(id) ON DELETE CASCADE;

ALTER TABLE tibia_claimed_player ADD CONSTRAINT fk_id_tibia FOREIGN KEY (fk_id_tibia)
      REFERENCES tibia(id) ON DELETE CASCADE;

ALTER TABLE tibia_claimed_player ADD CONSTRAINT fk_id_claimed_city FOREIGN KEY (fk_id_claimed_city)
      REFERENCES tibia_claimed_city(id) ON DELETE CASCADE;

