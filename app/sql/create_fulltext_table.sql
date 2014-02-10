DROP TABLE IF EXISTS item_search;
DROP TRIGGER IF EXISTS trigger_insert_search_item;
DROP TRIGGER IF EXISTS trigger_update_search_item;
DROP TRIGGER IF EXISTS trigger_delete_search_item;

DROP TRIGGER IF EXISTS trigger_insert_search_thema;
DROP TRIGGER IF EXISTS trigger_update_search_thema;
DROP TRIGGER IF EXISTS trigger_delete_search_thema;

DROP TRIGGER IF EXISTS trigger_insert_search_persoon;
DROP TRIGGER IF EXISTS trigger_update_search_persoon;
DROP TRIGGER IF EXISTS trigger_delete_search_persoon;

DROP TRIGGER IF EXISTS trigger_insert_search_kandidaat;
DROP TRIGGER IF EXISTS trigger_update_search_kandidaat;
DROP TRIGGER IF EXISTS trigger_delete_search_kandidaat;

DROP TRIGGER IF EXISTS trigger_insert_search_hoofdstuk;
DROP TRIGGER IF EXISTS trigger_update_search_hoofdstuk;
DROP TRIGGER IF EXISTS trigger_delete_search_hoofdstuk;

DROP TRIGGER IF EXISTS trigger_insert_search_wijk;
DROP TRIGGER IF EXISTS trigger_update_search_wijk;
DROP TRIGGER IF EXISTS trigger_delete_search_wijk;

DROP TRIGGER IF EXISTS trigger_insert_search_wijkparel;
DROP TRIGGER IF EXISTS trigger_update_search_wijkparel;
DROP TRIGGER IF EXISTS trigger_delete_search_wijkparel;


DROP PROCEDURE IF EXISTS index_item_search;


CREATE TABLE item_search 
(
    id int primary key auto_increment,
    object_id int,
    object_type enum('item','thema','kandidaat','hoofdstuk','wijk','wijkparel'),
    search_text TEXT, 
    keywords TEXT,
    FULLTEXT (keywords),
    FULLTEXT (search_text)

) ENGINE="MyISAM";

CREATE TRIGGER trigger_insert_search_item
AFTER INSERT ON PublishedItem
FOR EACH ROW
INSERT INTO item_search SET object_id=NEW.id, object_type='item', keywords=NEW.zoek_trefwoorden, search_text=NEW.zoek_tekst;


CREATE TRIGGER trigger_update_search_item
AFTER UPDATE ON PublishedItem
FOR EACH ROW
UPDATE item_search SET keywords=NEW.zoek_trefwoorden, search_text=NEW.zoek_tekst WHERE object_id=OLD.id AND object_type='item';

CREATE TRIGGER trigger_delete_search_item
AFTER DELETE ON PublishedItem
FOR EACH ROW
DELETE FROM item_search WHERE object_id=OLD.id AND object_type='item';

#Thema
CREATE TRIGGER trigger_insert_search_thema
AFTER INSERT ON Thema
FOR EACH ROW
INSERT INTO item_search SET object_id=NEW.id, object_type='thema', keywords=NULL, search_text=LOWER(CONCAT(NEW.titel,' ',NEW.tekst));


CREATE TRIGGER trigger_update_search_thema
AFTER UPDATE ON Thema
FOR EACH ROW
UPDATE item_search SET search_text=LOWER(CONCAT(NEW.titel,' ',NEW.tekst)) WHERE object_id=OLD.id AND object_type='thema';

CREATE TRIGGER trigger_delete_search_thema
AFTER DELETE ON Thema
FOR EACH ROW
DELETE FROM item_search WHERE object_id=OLD.id AND object_type='thema';

#Kandidaat

CREATE TRIGGER trigger_insert_search_kandidaat
AFTER INSERT ON Kandidaat
FOR EACH ROW
INSERT INTO item_search SET object_id=NEW.id, object_type='kandidaat', keywords=NULL, search_text=LOWER(CONCAT(NEW.naam,' ',NEW.personalia));


CREATE TRIGGER trigger_update_search_kandidaat
AFTER UPDATE ON Kandidaat
FOR EACH ROW
UPDATE item_search SET search_text=LOWER(CONCAT(NEW.naam,' ',NEW.personalia)) WHERE object_id=OLD.id AND object_type='kandidaat';

CREATE TRIGGER trigger_delete_search_kandidaat
AFTER DELETE ON Kandidaat
FOR EACH ROW
DELETE FROM item_search WHERE object_id=OLD.id AND object_type='kandidaat';

#Hoofdstuk
CREATE TRIGGER trigger_insert_search_hoofdstuk
AFTER INSERT ON Hoofdstuk
FOR EACH ROW
INSERT INTO item_search SET object_id=NEW.id, object_type='hoofdstuk', keywords=NULL, search_text=LOWER(CONCAT(NEW.titel,' ',NEW.tekst));


CREATE TRIGGER trigger_update_search_hoofdstuk
AFTER UPDATE ON Hoofdstuk
FOR EACH ROW
UPDATE item_search SET search_text=LOWER(CONCAT(NEW.titel,' ',NEW.tekst)) WHERE object_id=OLD.id AND object_type='hoofdstuk';

CREATE TRIGGER trigger_delete_search_hoofdstuk
AFTER DELETE ON Hoofdstuk
FOR EACH ROW
DELETE FROM item_search WHERE object_id=OLD.id AND object_type='hoofdstuk';

#Wijk
CREATE TRIGGER trigger_insert_search_wijk
AFTER INSERT ON Wijk
FOR EACH ROW
INSERT INTO item_search SET object_id=NEW.id, object_type='wijk', keywords=NULL, search_text=LOWER(CONCAT(NEW.wijk,' ',NEW.tekst));


CREATE TRIGGER trigger_update_search_wijk
AFTER UPDATE ON Wijk
FOR EACH ROW
UPDATE item_search SET search_text=LOWER(CONCAT(NEW.wijk,' ',NEW.tekst)) WHERE object_id=OLD.id AND object_type='wijk';

CREATE TRIGGER trigger_delete_search_wijk
AFTER DELETE ON Wijk
FOR EACH ROW
DELETE FROM item_search WHERE object_id=OLD.id AND object_type='wijk';

#WijkParel
CREATE TRIGGER trigger_insert_search_wijkparel
AFTER INSERT ON WijkParel
FOR EACH ROW
INSERT INTO item_search SET object_id=NEW.id, object_type='wijkparel', keywords=NULL, search_text=LOWER(CONCAT(NEW.titel,' ',NEW.tekst));


CREATE TRIGGER trigger_update_search_wijkparel
AFTER UPDATE ON WijkParel
FOR EACH ROW
UPDATE item_search SET search_text=LOWER(CONCAT(NEW.titel,' ',NEW.tekst)) WHERE object_id=OLD.id AND object_type='wijkparel';

CREATE TRIGGER trigger_delete_search_wijkparel
AFTER DELETE ON WijkParel
FOR EACH ROW
DELETE FROM item_search WHERE object_id=OLD.id AND object_type='wijkparel';

#Rebuild search index


DELETE FROM item_search;
INSERT INTO item_search (object_id,object_type,keywords,search_text) SELECT id, 'item', zoek_trefwoorden,zoek_tekst FROM PublishedItem;
INSERT INTO item_search (object_id,object_type,keywords,search_text) SELECT id, 'thema', NULL,LOWER(CONCAT(titel,' ',tekst)) FROM Thema;
INSERT INTO item_search (object_id,object_type,keywords,search_text) SELECT id, 'kandidaat', NULL,LOWER(CONCAT(naam,' ',personalia)) FROM Kandidaat;
INSERT INTO item_search (object_id,object_type,keywords,search_text) SELECT id, 'hoofdstuk', NULL,LOWER(CONCAT(titel,' ',tekst)) FROM Hoofdstuk;
INSERT INTO item_search (object_id,object_type,keywords,search_text) SELECT id, 'wijk', NULL,LOWER(CONCAT(wijk,' ',tekst)) FROM Wijk;
INSERT INTO item_search (object_id,object_type,keywords,search_text) SELECT id, 'wijkparel', NULL,LOWER(CONCAT(titel,' ',tekst)) FROM WijkParel;


UPDATE PublishedItem,publisheditem_trefwoord pt, Trefwoord t  SET zoek_trefwoorden = IF( zoek_trefwoorden IS NULL, LOWER(t.trefwoord),LOWER(CONCAT_WS(' ',zoek_trefwoorden,t.trefwoord))) WHERE pt.publisheditem_id=PublishedItem.id AND t.id=pt.trefwoord_id;