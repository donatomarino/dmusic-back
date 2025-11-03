USE dmusic_db;

-- Insertamos artistas
INSERT INTO Artists (full_name, avatar) VALUES
('Bad Bunny', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSjPfQxfIDmlM8kL_JN2sTrArKznP__WLxIXw&s'),
('Charlotte De Witte', 'https://unvrs.b-cdn.net/images/2025_UNVRS_CHARLOTTE-DE-WITTE_WEBSITE_EVENT-IMAGE_2400x1440px-1750174035.jpg'),
('Baby Gang', 'https://static1.personality-database.com/profile_images/f4ea535e43c74e3cb30dc18af1b664eb.png'),
('Karol G', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS3nORetF0EY_qOtXjpbpmp9KWpFt7sk_VwVA&s'),
('Shakira', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ539t66fzB7J4m8KPgh5meWDI9OnWd0Wc8ow&s'),
('Karima', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRp87V-r-tYrHVTYHCxLMRKCCim3gBJ5mXjtg&s'),
('Cazzu', 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/ca/Cazzu_en_2019_4.jpg/250px-Cazzu_en_2019_4.jpg'),
('Adele', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQtzbUtdL485w4Ql_LNIbHYtPv0Ku-Fx7CIlg&s'),
('Luis Fonsi', 'https://images.sk-static.com/images/media/profile_images/artists/328569/huge_avatar'),
('Klangkuenstler', 'https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTJHHqZ_-0pr5vCbBd9B_JyQOaJ36FCg1v5L8sNK_PSbgN4BIhqeWf0REJDcF0Tiu-ew4V2m2tCoYyqmDeM5axGjProeL5dZ4joja6r_w');

-- Insertamos canciones
INSERT INTO Songs(title, genre, url, image, id_artist) VALUES
('Baile InoLVIDABLE', 'Raggaetton', 'storage/music/BadBunny_BAILEINoLVIDABLE_1.mp3', 'https://t2.genius.com/unsafe/504x504/https%3A%2F%2Fimages.genius.com%2F66f08db4c1d9d323ab441ab6c04a034a.1000x1000x1.png', 1),
('The Age Of Love', 'Tecno','storage/music/Charlotte_AgeOfLove_2.mp3', 'https://t2.genius.com/unsafe/600x0/https%3A%2F%2Fimages.genius.com%2F3d4759fb8e0c210198bb7c2e9a1f92b1.500x500x1.jpg', 2),
('Tu Me Quieres', 'Trap', 'storage/music/BabyGang_TuMeQuieres_3.mp3', 'https://t2.genius.com/unsafe/504x504/https%3A%2F%2Fimages.genius.com%2F239bf8df4dfb37b7c74c40cba9136498.1000x1000x1.png',  3),
('DeBÍ TiRAR MáS FOToS', 'storage/Ragaetton', 'music/BadBunny_NUEVAYoL_1.mp3', 'https://t2.genius.com/unsafe/600x600/https%3A%2F%2Fimages.genius.com%2F66f08db4c1d9d323ab441ab6c04a034a.1000x1000x1.png', 1),
('Pero Tú', 'Ragaetton', 'storage/music/KarolG_PeroTú_4.mp3', 'https://t2.genius.com/unsafe/504x504/https%3A%2F%2Fimages.genius.com%2F281b83d9beed781e49c498f400afa4f5.640x640x1.jpg', 4),
('Someone Like You', 'Ballad', 'storage/music/Adele_SomeoneLikeYou_8.mp3', 'https://t2.genius.com/unsafe/504x504/https%3A%2F%2Fimages.genius.com%2F48caff7f3cd18b4f4e9b2db1baf3d576.1000x1000x1.png', 8),
('Despacito', 'Reggaeton', 'storage/music/LuisFonsi_Despacito_9.mp3', 'https://t2.genius.com/unsafe/504x504/https%3A%2F%2Fimages.genius.com%2F6dbadaf716039dad3841a1640755ac3a.1000x1000x1.png', 9),
('REBOTA', 'Ragaetton', 'storage/music/Karima_Rebota_6.mp3', 'https://t2.genius.com/unsafe/504x504/https%3A%2F%2Fimages.genius.com%2Fc69cd2fbcfbd69a99c7a445754a4c679.300x300x1.jpg', 6),
('DOLCE', 'Latino', 'storage/music/Cazzu_DOLCE_7.mp3', 'https://t2.genius.com/unsafe/504x504/https%3A%2F%2Fimages.genius.com%2F95a555bd9a68b4ce7bf726b3cdedc248.1000x1000x1.png', 7),
('Soltera', 'Latino', 'storage/music/Shakira_Soltera_5.mp3', 'https://t2.genius.com/unsafe/504x504/https%3A%2F%2Fimages.genius.com%2F591fd77de49c251aa4e05691166a60b5.1000x1000x1.png', 5),
('GIRL LIKE ME', 'Latino', 'storage/music/Shakira_GIRLLIKEME_5.mp3', 'https://t2.genius.com/unsafe/504x504/https%3A%2F%2Fimages.genius.com%2F124f3cddce7532eb26594b949010ae8f.1000x1000x1.jpg', 5),
('Roar', 'Tecno', 'storage/music/Charlotte_Roar_2.mp3', 'https://t2.genius.com/unsafe/504x504/https%3A%2F%2Fimages.genius.com%2Fedc02ee11001ed707a21838f4376f696.1000x1000x1.png', 2),
('Ma Chérie', 'Trap', 'storage/music/BabyGang_MaChérie_3.mp3', 'https://t2.genius.com/unsafe/504x504/https%3A%2F%2Fimages.genius.com%2F17bbb5e6470ce849dcc3df2f294345eb.1000x1000x1.jpg', 3),
('Sonne Geht Auf', 'Tecno', 'storage/music/SonneGehtAufh.mp3', 'https://t2.genius.com/unsafe/600x0/https%3A%2F%2Fimages.genius.com%2F5241139fc5bd6341bbdd2e197d03fa64.1000x1000x1.png', 10),
('Toter Schmetterling', 'Tecno', 'storage/music/ToterSchmetterling.mp3', 'https://t2.genius.com/unsafe/516x516/https%3A%2F%2Fimages.genius.com%2F6c9cc283a8009bd5cbc478c9873a11bd.1000x1000x1.png', 10);