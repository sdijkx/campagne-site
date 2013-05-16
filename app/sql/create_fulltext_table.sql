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


DROP PROCEDURE IF EXISTS index_item_search;


CREATE TABLE item_search 
(
    id int primary key auto_increment,
    object_id int,
    object_type enum('item','thema','persoon'),
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

#Persoon

CREATE TRIGGER trigger_insert_search_persoon
AFTER INSERT ON Persoon
FOR EACH ROW
INSERT INTO item_search SET object_id=NEW.id, object_type='persoon', keywords=NULL, search_text=LOWER(CONCAT(NEW.naam,' ',NEW.functie,' ',NEW.personalia));


CREATE TRIGGER trigger_update_search_persoon
AFTER UPDATE ON Persoon
FOR EACH ROW
UPDATE item_search SET search_text=LOWER(CONCAT(NEW.naam,' ',NEW.functie,' ',NEW.personalia)) WHERE object_id=OLD.id AND object_type='persoon';

CREATE TRIGGER trigger_delete_search_persoon
AFTER DELETE ON Persoon
FOR EACH ROW
DELETE FROM item_search WHERE object_id=OLD.id AND object_type='persoon';

#Rebuild search index


DELETE FROM item_search;
INSERT INTO item_search (object_id,object_type,keywords,search_text) SELECT id, 'item', zoek_trefwoorden,zoek_tekst FROM PublishedItem;
INSERT INTO item_search (object_id,object_type,keywords,search_text) SELECT id, 'thema', NULL,LOWER(CONCAT(titel,' ',tekst)) FROM Thema;
INSERT INTO item_search (object_id,object_type,keywords,search_text) SELECT id, 'persoon', NULL,LOWER(CONCAT(naam,' ',functie,' ',personalia)) FROM Persoon;

UPDATE PublishedItem,publisheditem_trefwoord pt, Trefwoord t  SET zoek_trefwoorden = IF( zoek_trefwoorden IS NULL, LOWER(t.trefwoord),LOWER(CONCAT_WS(' ',zoek_trefwoorden,t.trefwoord))) WHERE pt.publisheditem_id=PublishedItem.id AND t.id=pt.trefwoord_id;