-- CREATE SCHEMA

CREATE SCHEMA IF NOT EXISTS slotify
DEFAULT CHARACTER SET utf8;

USE slotify;

-- CREATE USERS TABLE

CREATE TABLE IF NOT EXISTS users (
  user_id			    INTEGER			  NOT NULL	AUTO_INCREMENT PRIMARY KEY,
  username	    	VARCHAR(25)		NOT NULL,
	first_name		  VARCHAR(25)		NOT NULL,
	last_name		    VARCHAR(25)		NOT NULL,
  email			      VARCHAR(200)	NOT NULL,
  password	    	VARCHAR(32)		NOT NULL,
  lp_album		    INTEGER			  NOT NULL	DEFAULT 1,
  lp_album_order	INTEGER		    NOT NULL	DEFAULT 1,
  created_date	  DATETIME		  NOT NULL	DEFAULT NOW(),
  updated_date	  DATETIME		  NOT NULL	DEFAULT NOW() ON UPDATE NOW(),
  profile_pic		  VARCHAR(500)
);


-- CREATE SYSTEMS TABLE
CREATE TABLE IF NOT EXISTS systems (
  system_id		  INTEGER			  NOT NULL	AUTO_INCREMENT PRIMARY KEY,
  name			    VARCHAR(50)		NOT NULL
);

-- CREATE ARTISTS TABLE
CREATE TABLE IF NOT EXISTS artists (
  artist_id		  INTEGER			  NOT NULL	AUTO_INCREMENT PRIMARY KEY,
  name			    VARCHAR(50)		NOT NULL
);

-- CREATE ALBUMS TABLE
CREATE TABLE IF NOT EXISTS albums (
  album_id		  INTEGER			  NOT NULL	AUTO_INCREMENT PRIMARY KEY,
  title_name		VARCHAR(250)	NOT NULL,
  system_id		  INTEGER			  NOT NULL,
  artwork_path	VARCHAR(500)	NOT NULL,
  year_released	INTEGER			  NOT NULL,

  FOREIGN KEY (system_id)
    REFERENCES systems(system_id)
);


-- CREATE SONGS TABLE
CREATE TABLE IF NOT EXISTS songs (
  song_id			  INTEGER			  NOT NULL	AUTO_INCREMENT PRIMARY KEY,
  title_name		VARCHAR(250)	NOT NULL,
  artist_id		  INTEGER			  NOT NULL,
  album_id		  INTEGER			  NOT NULL,
  duration		  INTEGER			  NOT NULL,
  song_path		  VARCHAR(500)	NOT NULL,
  album_order		INTEGER		  	NOT NULL,
  play_count		INTEGER			  NOT NULL	DEFAULT 0,

  FOREIGN KEY (artist_id)
    REFERENCES artists(artist_id),
  FOREIGN KEY (album_id)
    REFERENCES albums(album_id)
);

CREATE TABLE IF NOT EXISTS playlists (
  playlist_id		INTEGER		  	NOT NULL	AUTO_INCREMENT PRIMARY KEY,
  name			    VARCHAR(50)		NOT NULL,
  user_id			  INTEGER			  NOT NULL,
  created_at		DATETIME		  NOT NULL	DEFAULT NOW(),
  updated_at		DATETIME		  NOT NULL	DEFAULT NOW() ON UPDATE NOW(),

  FOREIGN KEY (user_id)
    REFERENCES users(user_id)
);

CREATE TABLE IF NOT EXISTS pl_songs (
  pl_song_id	  	INTEGER			NOT NULL	AUTO_INCREMENT PRIMARY KEY,
  song_id			    INTEGER			NOT NULL,
  playlist_id	  	INTEGER			NOT NULL,
  playlist_order	INTEGER			NOT NULL,

  FOREIGN KEY (song_id)
    REFERENCES songs(song_id),
  FOREIGN KEY (playlist_id)
    REFERENCES playlists(playlist_id)
);

CREATE TABLE IF NOT EXISTS saved_albums (
  user_id			  INTEGER			NOT NULL,
  album_id		  INTEGER			NOT NULL,
  created_at		DATETIME		NOT NULL	DEFAULT NOW(),

  FOREIGN KEY (user_id)
    REFERENCES users(user_id),
  FOREIGN KEY (album_id)
    REFERENCES albums(album_id)
);

CREATE TABLE IF NOT EXISTS saved_songs (
  user_id			  INTEGER			NOT NULL,
  song_id			  INTEGER			NOT NULL,
  created_at		DATETIME		NOT NULL	DEFAULT NOW(),

  FOREIGN KEY (user_id)
    REFERENCES users(user_id),
  FOREIGN KEY (song_id)
    REFERENCES songs(song_id)
);

-- CREATE ARTISTS TABLE
CREATE TABLE IF NOT EXISTS album_artists (
  album_id		INTEGER			NOT NULL,
  artist_id		INTEGER			NOT NULL,

  FOREIGN KEY (album_id)
    REFERENCES albums(album_id),
  FOREIGN KEY (artist_id)
    REFERENCES artists(artist_id)
);

--
-- Dumping data for table `artists`
--
INSERT INTO users
(username, first_name, last_name, email, password)
VALUES
('Guest', 'Guest', 'User', 'guest@slotify.com', '123456')
;

INSERT INTO artists 
(name) 
VALUES
('Koji Kondo'),
('Takashi Tateishi'),
('Hirokazu Tanaka'),
('Konami Kukeiha Club'),
('Nobuo Uematsu'),
('Yoko Shimomura'),
('Isao Abe'),
('Junichi Masuda'),
('Matt Uelmen')
;

--
-- Dumping data for table `albums`
--

INSERT INTO systems
(name)
VALUES
('NES'),
('SNES'),
('PlayStation'),
('GameBoy'),
('PC')
;

INSERT INTO albums 
(year_released, system_id, title_name, artwork_path) 
VALUES
(1986, 1,	'The Legend of Zelda',	'zelda.jpg'),
(1988, 1,	'Mega Man 2',			      'megaman2.jpg'),
(1987, 1,	'Punch Out',			      'punchout.jpg'),
(1986, 1,	'Metroid',				      'metroid.jpg'),
(1987, 1,	'Contra',				        'contra.jpg'),
(1985, 1,	'Super Mario Bros.',	  'supermariobros.jpg'),
(1991, 2,	'Final Fantasy 4',		  'finalfantasy4.jpg'),
(1991, 2,	'Street Fighter 2',		  'streetfighter2.jpg'),
(1997, 3,	'Final Fantasy 7',		  'finalfantasy7.jpg'),
(1996, 4,	'Pokemon: Red & Blue',  'pokemon.jpg'),
(1989, 4,	'Tetris',               'tetris.jpg'),
(2000, 5,	'Diablo 2',             'diablo2.jpg')
;

--
-- Dumping data for table `album_artists`
--

INSERT INTO album_artists 
(album_id, artist_id) 
VALUES
(1, 1),
(2, 2),
(3, 1),
(4, 3),
(5, 4),
(6, 1),
(7, 5),
(8, 6),
(8, 7),
(9, 5),
(10, 8),
(11, 3),
(12, 9)
;

--
-- Dumping data for table `Songs`
--

INSERT INTO songs 
(title_name, artist_id, album_id, duration, song_path, album_order)
VALUES
('Title Theme',					1, 1, '180',	'zelda/01_title_theme.mp3',			  	1),
('Overworld Theme',			1, 1, '180',	'zelda/03_overworld_theme.mp3',			2),
('Dungeon Theme',				1, 1, '180',	'zelda/04_dungeon_theme.mp3',		  	3),
('Dungeon Clear',				1, 1, '9',		'zelda/05_dungeon_clear.mp3',		  	4),
('Receive Item',				1, 1, '3',		'zelda/06_receive_item.mp3',		  	5),
('Recorder Theme',			1, 1, '4',		'zelda/07_recorder_theme.mp3',			6),
('Life Lost',						1, 1, '4',		'zelda/08_life_lost.mp3',				    7),
('Rescued',							1, 1, '6',		'zelda/09_rescued.mp3',					    8),
('Ganon Dungeon',				1, 1, '180',	'zelda/10_ganon_dungeon.mp3',		  	9),
('Ganon Defeated',			1, 1, '4',		'zelda/11_ganon_defeated.mp3',			10),
('Ending Theme',				1, 1, '180',	'zelda/12_ending_theme.mp3',			  11),

('Introduction',				2, 2, '43',		'megaman2/01_introduction.mp3',			1),
('Title Screen',				2, 2, '44',		'megaman2/02_title_screen.mp3',			2),
('Password Screen',			2, 2, '34',		'megaman2/03_password_screen.mp3',	3),
('Stage Select',				2, 2, '32',		'megaman2/04_stage_select.mp3',			4),
('Enemy Chosen',				2, 2, '8',		'megaman2/05_enemy_chosen.mp3',			5),
('Quick Man',						2, 2, '85',		'megaman2/06_quick_man.mp3',		  	6),
('Metal Man',						2, 2, '87',		'megaman2/07_metal_man.mp3',		  	7),
('Bubble Man',					2, 2, '83',		'megaman2/08_bubble_man.mp3',		  	8),
('Heat Man',						2, 2, '61',		'megaman2/09_heat_man.mp3',			  	9),
('Wood Man',						2, 2, '81',		'megaman2/10_wood_man.mp3',			  	10),
('Air Man',							2, 2, '100',	'megaman2/11_air_man.mp3',			  	11),
('Crash Man',						2, 2, '150',	'megaman2/12_crash_man.mp3',		  	12),
('Flash Man',						2, 2, '137',	'megaman2/13_flash_man.mp3',		  	13),
('Boss Battle',					2, 2, '42',		'megaman2/14_boss_battle.mp3',			14),
('Victory',							2, 2, '7',		'megaman2/15_victory.mp3',			  	15),
('Weapons Ready',				2, 2, '30',		'megaman2/16_weapons_ready.mp3',		16),
('Dr Wily\'s Map',			2, 2, '9',		'megaman2/17_wilys_map.mp3',		  	17),
('Dr Wily\'s Castle',		2, 2, '158',	'megaman2/18_wilys_castle.mp3',			18),
('Dr Wily\'s Castle2',	2, 2, '164',	'megaman2/19_wilys_castle2.mp3',		19),
('Dr Wily Defeated',		2, 2, '11',		'megaman2/20_wily_defeated.mp3',		20),
('Epilogue',						2, 2, '70',		'megaman2/21_epilogue.mp3',			  	21),
('Credits',							2, 2, '67',		'megaman2/22_credits.mp3',			  	22),
('Game Over',						2, 2, '6',		'megaman2/23_game_over.mp3',		  	23),

('Fight Theme',					1, 3, '180',	'punchout/fight_theme.mp3',			    1),

('Title',							  3, 4, '110',	'metroid/01_title.mp3',					    1),
('Samus Entry',					3, 4, '7',		'metroid/02_samus_entry.mp3',		    2),
('Brinstar',		        3, 4, '106',	'metroid/03_brinstar.mp3',			    3),
('Boss Room (Kraid)',	  3, 4, '100',	'metroid/04_mini_boss_room1.mp3',		4),
('Norfair',		          3, 4, '80',		'metroid/05_norfair.mp3',				    5),
('Boss Room (Ridle)',	  3, 4, '61',		'metroid/06_mini_boss_room2.mp3',		6),
('Silence',							3, 4, '29',		'metroid/07_silence.mp3',			    	7),
('Item Acquisition',		3, 4, '6',		'metroid/08_item_jingle.mp3',			  8),
('Tourian',				      3, 4, '40',		'metroid/09_tourian_base_stage.mp3',9),
('The Lord of Zebes',		3, 4, '34',		'metroid/10_the_lord_of_zebes.mp3',	10),
('Escape',							3, 4, '87',		'metroid/11_escape.mp3',				    11),
('Ending',							3, 4, '136',	'metroid/12_ending.mp3',				    12),

('Title',								4, 5, '7',		'contra/01_title.mp3',					    1),
('Introduction',				4, 5, '65',		'contra/02_introduction.mp3',			  2),
('Areas 1 & 7',		      4, 5, '111',	'contra/03_jungle_hanger.mp3',			3),
('Area Clear',					4, 5, '6',		'contra/04_area_clear.mp3',				  4),
('Areas 2 & 4',				  4, 5, '99',		'contra/05_areas_2_4_bases_1_2.mp3',5),
('Boss',								4, 5, '80',		'contra/06_boss.mp3',					      6),
('Area 3: Waterfall',		4, 5, '83',		'contra/07_area_3_waterfall.mp3',		7),
('Area 5: Snow Field',	4, 5, '77',		'contra/08_area_5_snow_field.mp3',	8),
('Area 6: Energy Zone',	4, 5, '54',		'contra/09_area_6_energy_zone.mp3',	9),
('Area 8: Alien Lair',	4, 5, '67',		'contra/10_area_8_aliens_lair.mp3',	10),
('Area 8: Alen Dead',		4, 5, '10',		'contra/11_area_8_alien_dead.mp3',	11),
('Credit',							4, 5, '85',		'contra/12_credit.mp3',					    12),
('Game Over',						4, 5, '7',		'contra/13_game_over.mp3',				  13),

('Overworld Theme',			1, 6, '184',	'mario/01_overworld_theme.mp3',			1),
('Underworld Theme',		1, 6, '73',		'mario/02_underworld_theme.mp3',		2),
('Underwater Theme',		1, 6, '120',	'mario/03_underwater_theme.mp3',		3),
('Castle Theme',				1, 6, '69',		'mario/04_castle_theme.mp3',			  4),
('Starman Theme',				1, 6, '74',		'mario/05_starman_theme.mp3',			  5),
('Level Clear Fanfare',	1, 6, '8',		'mario/06_level_clear_fanfare.mp3',	6),
('Castle ClearFanfare', 1, 6, '9',		'mario/07_castle_clear_theme.mp3',	7),
('You\'re Dead',	      1, 6, '6',		'mario/08_youre_dead.mp3',				  8),
('Game Over',			      1, 6, '7',		'mario/09_game_over.mp3',				    9),
('Game Over2',		      1, 6, '6',		'mario/10_game_over2.mp3',			    10),
('Into the Tunnel',		  1, 6, '5',		'mario/11_into_tunnel.mp3',			  	11),
('Hurry Fanfare',				1, 6, '3',		'mario/12_hurry_fanfare.mp3',		  	12),
('Hurry Overworld',			1, 6, '130',	'mario/13_hurry_overworld.mp3',			13),
('Hurry Underworld',		1, 6, '74',		'mario/14_hurry_underworld.mp3',		14),
('Hurry Castle Theme',	1, 6, '72',		'mario/15_hurry_castle.mp3',		  	15),
('Ending Fanfare',			1, 6, '77',		'mario/16_ending_fanfare.mp3',			16),

('Prelude',								    5, 7, '72',		'ff4/01_prelude.mp3',					1),
('Red Wings',							    5, 7, '126',	'ff4/02_red_wings.mp3',				2),
('Kingdom of Baron',					5, 7, '69',		'ff4/03_kingdom_baron.mp3',		3),
('Love Theme',								5, 7, '109',	'ff4/04_love_theme.mp3',			4),
('Prologue',								  5, 7, '71',		'ff4/05_prologue.mp3',				5),
('Welcome to Our Town',				5, 7, '049',	'ff4/06_welcome_town.mp3',		6),
('Main Theme',								5, 7, '93',		'ff4/07_main_theme.mp3',			7),
('Fight 1',									  5, 7, '60',		'ff4/08_fight1.mp3',					8),
('Fanfare',									  5, 7, '26',		'ff4/09_fanfare.mp3',					9),
('Hello, Big Chocobo',				5, 7, '26',		'ff4/10_big_chocobo.mp3',			10),
('Chocobo, Chocobo',					5, 7, '29',		'ff4/11_chocobo_chocobo.mp3',	11),
('Into the Darkness',					5, 7, '81',		'ff4/12_into_darkness.mp3',		12),
('Fight 2',									  5, 7, '74',		'ff4/13_fight2.mp3',					13),
('Ring of Bombs',							5, 7, '52',		'ff4/14_ring_of_bomb.mp3',		14),
('Rydia',								     	5, 7, '61',		'ff4/15_rydia.mp3',						15),
('Castle Damcyan',						5, 7, '64',		'ff4/16_castle_damcyan.mp3',	16),
('Cry in Sorrow',							5, 7, '60',		'ff4/17_cry_in_sorrow.mp3',		17),
('Melody of the Lute',				5, 7, '55',		'ff4/18_melody_of_lute.mp3',	18),
('Mt Ordeals',								5, 7, '78',		'ff4/19_mt_ordeals.mp3',			19),
('Fabul',									    5, 7, '95',		'ff4/20_fabul.mp3',						20),
('Run',									    	5, 7, '25',		'ff4/21_run.mp3',						  21),
('Suspicion',						  		5, 7, '38',		'ff4/22_suspicion.mp3',				22),
('Golbez',							  		5, 7, '60',		'ff4/23_golbez.mp3',					23),
('Hey, Cid',						  		5, 7, '56',		'ff4/24_hey_cid.mp3',					24),
('Mystic Mysidia',						5, 7, '80',		'ff4/25_mysidia.mp3',					25),
('Long Way to Go',						5, 7, '46',		'ff4/26_long_way_to_go.mp3',	26),
('Palom & Porom',							5, 7, '36',		'ff4/27_palom_porom.mp3',			27),
('Battle of the Four Fiends',	5, 7, '100',	'ff4/28_four_fiends.mp3',			28),
('The Airship',								5, 7, '55',		'ff4/29_airship.mp3',					29),
('Trojan Beauty',							5, 7, '83',		'ff4/30_trojan_beauty.mp3',		30),
('Samba de Chocobo',					5, 7, '45',		'ff4/31_samba_chocobo.mp3',		31),
('Tower of Babel',						5, 7, '92',		'ff4/32_tower_of_babel.mp3',	32),
('Somewhere in the World',		5, 7, '33',		'ff4/33_somewhere.mp3',				33),
('Land of the Dwarves',				5, 7, '53',		'ff4/34_land_of_dwarves.mp3',	34),
('Giotto, the Great King',		5, 7, '57',		'ff4/35_giotto.mp3',					35),
('Dancing Calcobrena',				5, 7, '32',		'ff4/36_calcobrena.mp3',			36),
('Tower of Zot',							5, 7, '65',		'ff4/37_tower_of_zot.mp3',		37),
('Illusionary World',					5, 7, '75',		'ff4/38_illusionary.mp3',			38),
('The Big Whale',							5, 7, '68',		'ff4/39_big_whale.mp3',				39),
('Another Moon',							5, 7, '66',		'ff4/40_another_moon.mp3',		40),
('The Lunarians',							5, 7, '77',		'ff4/41_lunarians.mp3',				41),
('Within the Giant',					5, 7, '87',		'ff4/42_within_giant.mp3',		42),
('Final Battle',							5, 7, '115',	'ff4/43_final_battle.mp3',		43),
('Epilogue',								  5, 7, '570',	'ff4/44_epilogue.mp3',				44),

('The World Warrior',				6, 8, '29',		'sf2/01_world_warrior.mp3',			1),
('Player Select',						6, 8, '45',		'sf2/02_player_select.mp3',			2),
('Start Battle',						7, 8, '4',		'sf2/03_start_battle.mp3',			3),
('Ryu',										  6, 8, '107',	'sf2/04_ryu.mp3',						    4),
('E. Honda',							  6, 8, '125',	'sf2/05_e_honda.mp3',					  5),
('Blanka',								  6, 8, '120',	'sf2/06_blanka.mp3',					  6),
('Guile',									  6, 8, '142',	'sf2/07_guile.mp3',						  7),
('Ken',										  6, 8, '133',	'sf2/08_ken.mp3',						    8),
('Chun li',								  6, 8, '113',	'sf2/09_chun_li.mp3',				  	9),
('Zangief',									6, 8, '107',	'sf2/10_zangief.mp3',				  	10),
('Dhalsim',									6, 8, '90',		'sf2/11_dhalsim.mp3',				  	11),
('End Battle',							6, 8, '5',		'sf2/12_end_battle.mp3',				12),
('Continue',								6, 8, '32',		'sf2/13_continue.mp3',					13),
('Game Over',								6, 8, '2',		'sf2/14_game_over.mp3',					14),
('Ranking',									6, 8, '2',		'sf2/15_ranking.mp3',				  	15),
('New Challenger',					7, 8, '2',		'sf2/16_new_challenger.mp3',		16),
('Bonus Stage',							6, 8, '33',		'sf2/17_bonus_stage.mp3',				17),
('Balrog',									6, 8, '110',	'sf2/18_balrog.mp3',				  	18),
('Vega',									  6, 8, '124',	'sf2/19_vega.mp3',					  	19),
('Sagat',									  7, 8, '175',	'sf2/20_sagat.mp3',					  	20),
('M. Bison',								6, 8, '137',	'sf2/21_bison.mp3',					  	21),
('Ryu\'s Ending',					  6, 8, '60',		'sf2/22_ryu_ending.mp3',				22),
('E. Honda\'s Ending',			6, 8, '56',		'sf2/23_honda_ending.mp3',			23),
('Blanka\'s Ending',				6, 8, '67',		'sf2/24_blanka_ending.mp3',			24),
('Guile\'s Ending',					6, 8, '55',		'sf2/25_guile_ending.mp3',			25),
('Ken\'s Ending 1',					6, 8, '61',		'sf2/26_ken_ending1.mp3',				26),
('Ken\'s Ending 2',				  6, 8, '45',		'sf2/27_ken_ending2.mp3',				27),
('Chun Li\'s Ending 1',			6, 8, '92',		'sf2/28_chun_li_ending1.mp3',		28),
('Chun Li\'s Ending 2',			6, 8, '45',		'sf2/29_chun_li_ending2.mp3',		29),
('Zangief\'s Ending',				6, 8, '35',		'sf2/30_zangief_ending.mp3',		30),
('Dhalsim\'s Ending',				6, 8, '33',		'sf2/31_dhalsim_ending.mp3',		31),
('Credits',									6, 8, '233',	'sf2/32_credits.mp3',				  	32),

('Prelude',									          5, 9, '173',	'ff7/01_prelude.mp3',				  	1),
('Opening, Bombing Mission',	        5, 9, '237',	'ff7/02_opening_mission.mp3', 	2),
('Mako Reactor',							        5, 9, '200',	'ff7/03_mako_reactor.mp3',	  	3),
('Heart of Anxiety',						      5, 9, '242',	'ff7/04_heart_anxiety.mp3',	  	4),
('Tifa\'s Theme',							        5, 9, '306',	'ff7/05_tifa_theme.mp3',		  	5),
('Barret\'s Theme',							      5, 9, '207',	'ff7/06_barret_theme.mp3',	  	6),
('Hurry!',									          5, 9, '149',	'ff7/07_hurry.mp3',					  	7),
('Lurking in the Darkness',					  5, 9, '150',	'ff7/08_lurking.mp3',				  	8),
('Shinra Company',							      5, 9, '242',	'ff7/09_shinra_co.mp3',			  	9),
('Those Who Fight',							      5, 9, '168',	'ff7/10_fight.mp3',					  	10),
('Fanfare',									          5, 9, '55',		'ff7/11_fanfare.mp3',				  	11),
('Flowers Blooming in the Church',		5, 9, '300',	'ff7/12_flowers_church.mp3',  	12),
('Turk\'s Theme',							        5, 9, '139',	'ff7/13_turk_theme.mp3',		  	13),
('Under the Rotting Pizza',					  5, 9, '204',	'ff7/14_rotting_pizza.mp3',	  	14),
('The Oppressed',							        5, 9, '157',	'ff7/15_oppressed.mp3',			  	15),
('Honeybee Inn',							        5, 9, '232',	'ff7/16_honeybee_inn.mp3',	  	16),
('Who... Are You',							      5, 9, '90',		'ff7/17_who_are_you.mp3',				17),
('Don of the Slums',						      5, 9, '130',	'ff7/18_don_slums.mp3',					18),
('Infiltrating Shinra',					  	  5, 9, '227',	'ff7/19_infiltrating_shinra.mp3',	19),
('Those Who Fight Further',					  5, 9, '211',	'ff7/20_fight_further.mp3',			20),
('Red XIII\'s Theme',						      5, 9, '87',		'ff7/21_red_13_theme.mp3',			21),
('Crazy Motorcycle',						      5, 9, '215',	'ff7/22_crazy_motorcycle.mp3',	22),
('Dear to the Heart',						      5, 9, '133',	'ff7/23_dear_heart.mp3',	 			23),
('Main Theme Final Fantasy VII',			5, 9, '388',	'ff7/24_main_theme.mp3',				24),
('On Our Way',								        5, 9, '223',	'ff7/25_on_our_way.mp3',				25),
('Good Night, Until Tomorrow',				5, 9, '10',		'ff7/26_good_night.mp3',				26),
('On that day, five years ago',				5, 9, '193',	'ff7/27_that_day.mp3',					27),
('Farm Boy',								          5, 9, '171',	'ff7/28_farm_boy.mp3',					28),
('Waltz de Chocobo',						      5, 9, '33',		'ff7/29_waltz_chocobo.mp3',			29),
('Electric de Chocobo',						    5, 9, '240',	'ff7/30_electric_chocobo.mp3',	30),
('Cinco de Chocobo',						      5, 9, '178',	'ff7/31_cinco_chocobo.mp3',			31),
('In Search of the Man in Black',			5, 9, '174',	'ff7/32_search_black.mp3',			32),
('Fort Condor',								        5, 9, '239',	'ff7/33_fort_condor.mp3',				33),
('Rufus\'s Wedding Ceremony',				  5, 9, '134',	'ff7/34_rufus_ceremony.mp3',		34),
('It\'s Hard to Stand on Both Feet!',	5, 9, '209',	'ff7/35_hard_to_stand.mp3',			35),
('Trail of Blood',							      5, 9, '252',	'ff7/36_trail_of_blood.mp3',		36),
('Jenova',									          5, 9, '150',	'ff7/37_jenova.mp3',					  37),
('Continue',								          5, 9, '35',		'ff7/38_continue.mp3',					38),
('Costa del Sol',							        5, 9, '146',	'ff7/39_costa_del_sol.mp3',			39),
('Mark of a Traitor',						      5, 9, '210',	'ff7/40_mark_traitor.mp3',			40),
('Mining Town',								        5, 9, '178',	'ff7/41_mining_town.mp3',				41),
('Gold Saucer',								        5, 9, '116',	'ff7/42_gold_saucer.mp3',				42),
('Cait Sith\'s Theme',						    5, 9, '213',	'ff7/43_cait_sith_theme.mp3',		43),
('Desert Wasteland',						      5, 9, '333',	'ff7/44_desert_wasteland.mp3',	44),
('Cosmo Canyon',							        5, 9, '214',	'ff7/45_cosmo_canyon.mp3',			45),
('Lifestream',								        5, 9, '214',	'ff7/46_lifestream.mp3',				46),
('The Great Warrior',						      5, 9, '203',	'ff7/47_great_warrior.mp3',			47),
('Descendant of Shinobi',					    5, 9, '163',	'ff7/48_descendant_shinobi.mp3',48),
('Those Chosen by the Planet',				5, 9, '195',	'ff7/49_those_chosen.mp3',			49),
('The Nightmare Begins',					    5, 9, '178',	'ff7/50_nightmare_begins.mp3',	50),
('Cid\'s Theme',							        5, 9, '188',	'ff7/51_cid_theme.mp3',					51),
('Steal the Tiny Bronco!',					  5, 9, '74',		'ff7/52_steal_tiny_bronco.mp3',	52),
('Wutai',									            5, 9, '268',	'ff7/53_wutai.mp3',						  53),
('Stolen Materia',							      5, 9, '95',		'ff7/54_stolen_materia.mp3',		54),
('Chocobo Racing - Place Your Bets!',	5, 9, '108',	'ff7/55_chocobo_place_bets.mp3',55),
('Fiddle de Chocobo',						      5, 9, '168',	'ff7/56_fiddle_chocobo.mp3',		56),
('Jackpot',									          5, 9, '45',		'ff7/57_jackpot.mp3',					  57),
('Tango of Tears',							      5, 9, '48',		'ff7/58_tango_tears.mp3',				58),
('Debut',									            5, 9, '156',	'ff7/59_debut.mp3',						  59),
('Words Drowned by Fireworks',				5, 9, '168',	'ff7/60_words_fireworks.mp3',		60),
('Forested Temple',							      5, 9, '229',	'ff7/61_forested_temple.mp3',		61),
('Listen to the Cries of the Planet',	5, 9, '218',	'ff7/62_cries_of_planet.mp3',		62),
('Aerith\'s Theme',							      5, 9, '257',	'ff7/63_aerith_theme.mp3',			63),
('Buried in Snow',							      5, 9, '289',	'ff7/64_burried_in_snow.mp3',		64),
('The North Cave',							      5, 9, '364',	'ff7/65_north_cave.mp3',				65),
('Reunion',									          5, 9, '212',	'ff7/66_reunion.mp3',					  66),
('Who... Am I',								        5, 9, '96',		'ff7/67_who_am_i.mp3',					67),
('Shinra\'s Full-Scale Assault',			5, 9, '175',	'ff7/68_shinra_assault.mp3',		68),
('Attack of the Weapon',					    5, 9, '170',	'ff7/69_attack_of_weapon.mp3',	69),
('The Highwind Takes to the Skies',		5, 9, '214',	'ff7/70_highwind_skies.mp3',		70),
('Secret of the Deep Sea',				  	5, 9, '254',	'ff7/71_secret_deep_sea.mp3',		71),
('Provincial Town',							      5, 9, '145',	'ff7/72_provincial_town.mp3',		72),
('From the Edge of Despair',				  5, 9, '253',	'ff7/73_edge_of_despair.mp3',		73),
('Other Side of the Mountain',				5, 9, '155',	'ff7/74_other_side_mountain.mp3',	74),
('Hurry Up!',								          5, 9, '176',	'ff7/75_hurry_up.mp3',					75),
('Launching a Dream Into Space',			5, 9, '168',	'ff7/76_launching_dream_space.mp3',	76),
('Countdown',								          5, 9, '49',		'ff7/77_countdown.mp3',					77),
('Open Your Heart',							      5, 9, '166',	'ff7/78_open_your_heart.mp3',		78),
('Mako Cannon - The Destruction of Shinra',	5, 9, '92',		'ff7/79_mako_cannon.mp3',	79),
('Judgement Day',							        5, 9, '245',	'ff7/80_judgement_day.mp3',			80),
('Jenova Complete',							      5, 9, '237',	'ff7/81_jenova_complete.mp3',		81),
('Birth of a God',							      5, 9, '250',	'ff7/82_birth_of_a_god.mp3',		82),
('One-Winged Angel',						      5, 9, '437',	'ff7/83_one_winged_angel.mp3',	83),
('The Planet\'s Crisis',					    5, 9, '482',	'ff7/84_planet_crisis.mp3',			84),
('Credits - Staff Roll',					    5, 9, '411',	'ff7/85_credits_staff_roll.mp3',85),

('Opening Theme',			  	      8, 10, '118', 'pokemon/01_opening.mp3',			  	    1),
('Palette Town Theme',          8, 10, '77',  'pokemon/02_palette_town_theme.mp3', 	2),
('Professor Oak', 		  	      8, 10, '45',  'pokemon/03_professor_oak.mp3',			  3),
('Oak Research Lab',		        8, 10, '42',  'pokemon/04_oak_research_lab.mp3',  	4),
('Rival Appears',				        8, 10, '40',  'pokemon/05_rival_appears.mp3',		  	5),
('Road to Viridian City',	      8, 10, '57',	'pokemon/06_road_viridian_city.mp3',	6),
('Battle vs Wild Pokemon',      8, 10, '86',	'pokemon/07_battle_vs_wild.mp3',      7),
('Victory vs Wild Pokemon',     8, 10, '32',	'pokemon/08_victory_vs_wild.mp3',	    8),
('Pewter City Theme',	  	      8, 10, '127',	'pokemon/09_pewter_city_theme.mp3',   9),
('Pokemon Center',		  	      8, 10, '70',	'pokemon/10_pokemon_center.mp3',	  	10),
('Pokemon Recovery',	  	      8, 10, '5',	  'pokemon/11_pokemon_recovery.mp3',		11),
('Viridian Forest',		  	      8, 10, '110',	'pokemon/12_viridian_forest.mp3',		  12),
('Guidepost',		        	      8, 10, '34',	'pokemon/13_guidepost.mp3',		        13),
('Trainer Appears Girl Chapter',8, 10, '22',	'pokemon/14_trainer_appears_girl.mp3',14),
('Battle vs Trainer',	  	      8, 10, '192',	'pokemon/15_battle_vs_trainer.mp3',   15),
('Victory vs Trainer',	 	      8, 10, '27',	'pokemon/16_victory_vs_trainer.mp3',	16),
('Mt Moon Cave',	 	            8, 10, '97',	'pokemon/17_mt_moon_cave.mp3',	      17),
('The Road to Cerulean',	      8, 10, '85',	'pokemon/18_road_to_cerulean.mp3',  	18),
('Cerulean City Theme',	 	      8, 10, '73',	'pokemon/19_cerulean_city_theme.mp3',	19),
('Pokemon Gym',	    	  	      8, 10, '72',	'pokemon/20_pokemon_gym.mp3',   	  	20),
('To Bill\'s Origin',	  	      8, 10, '48',	'pokemon/21_bills_origin.mp3',    		21),
('Jigglypuff\'s Song',  	      8, 10, '10',	'pokemon/22_jigglypuffs_song.mp3',	  22),
('Vermillion City Theme',       8, 10, '59',	'pokemon/23_vermillion_city.mp3',	    23),
('Codename St Anne',	  	      8, 10, '80',	'pokemon/24_codename_st_anne.mp3',    24),
('The Road to Lavender',	      8, 10, '74',	'pokemon/25_road_to_lavender.mp3',    25),
('Pokemon Whistle',	    	      8, 10, '12',	'pokemon/26_pokemon_whistle.mp3',	    26),
('Trainer Appears Boy Chapter', 8, 10, '26',	'pokemon/27_trainer_appears_boy.mp3',	27),
('Battle vs Gym Leader',	      8, 10, '116',	'pokemon/28_battle_vs_gym_leader.mp3',28),
('Victory vs Gym Leader',	      8, 10, '53', 'pokemon/29_victory_vs_gym_leader.mp3',29),
('Cycling',       		  	      8, 10, '80',	'pokemon/30_cycling.mp3',	          	30),
('Lavendar Town Theme',	 	      8, 10, '104', 'pokemon/31_lavender_town.mp3',    		31),
('Pokemon Tower',		  	        8, 10, '136',	'pokemon/32_pokemon_tower.mp3',	  	  32),
('Celadon City Theme',		      8, 10, '73',	'pokemon/33_celadon_city.mp3',	      33),
('Casino',	        	  	      8, 10, '87',	'pokemon/34_casino.mp3',              34),
('Trainer Appears Bad Guy Chapter',8, 10, '23','pokemon/35_trainer_appears_bad.mp3',35),
('Team Rocket Hideout',	 	      8, 10, '145',	'pokemon/36_team_rocket_hideout.mp3',	36),
('Sylph Company',	       	      8, 10, '78',	'pokemon/37_sylph_company.mp3',	      37),
('Ocean',	              	      8, 10, '90',	'pokemon/38_ocean.mp3',             	38),
('Cinnabar Islands',  	 	      8, 10, '59',	'pokemon/39_cinnabar_islands.mp3',  	39),
('Pokemon Mansion',		  	      8, 10, '80',	'pokemon/40_pokemon_mansion.mp3',    	40),
('Evolution',	          	      8, 10, '32',	'pokemon/41_evolution.mp3',	        	41),
('The Final Road',		  	      8, 10, '70',	'pokemon/42_the_final_road.mp3',      42),
('Last Battle vs Rival', 	      8, 10, '147',	'pokemon/43_last_battle.mp3',         43),
('Into The Palace',		  	      8, 10, '60',	'pokemon/44_into_the_palace.mp3',     44),
('Ending',	            	      8, 10, '102',	'pokemon/45_ending.mp3',              45),

('Title',			  	      3, 11, '40',  'tetris/01_title.mp3',	       	    1),
('A-Type',              3, 11, '180', 'tetris/02_a-type.mp3', 	          2),
('B-Type', 		  	      3, 11, '180', 'tetris/03_b-type.mp3',			        3),
('C-Type',		          3, 11, '180', 'tetris/04_c-type.mp3',  	          4),
('Fanfare Variation 1', 3, 11, '5',   'tetris/05_fanfare_variation1.mp3',	5),
('Fanfare Variation 2', 3, 11, '5', 	'tetris/06_fanfare_variation2.mp3',	6),
('Fanfare Variation 3', 3, 11, '5', 	'tetris/07_fanfare_variation3.mp3', 7),
('Fanfare Variation 4', 3, 11, '4',	  'tetris/08_fanfare_variation4.mp3', 8),
('Fanfare Variation 5', 3, 11, '8',	  'tetris/09_fanfare_variation5.mp3', 9),
('Fanfare Variation 6', 3, 11, '14',	'tetris/10_fanfare_variation6.mp3',	10),
('Game Menu',	  	      3, 11, '6',	  'tetris/11_menu.mp3',		            11),
('Almost Lost',		  	  3, 11, '180',	'tetris/12_almost_lost.mp3',		    12),
('Finish',		          3, 11, '2',	  'tetris/13_finish.mp3',		          13),
('Player Wins',         3, 11, '180',	'tetris/14_player_wins.mp3',        14),
('Ending',	  	        3, 11, '180',	'tetris/15_ending.mp3',             15),
('Game Over',	 	        3, 11, '3',	  'tetris/16_game_over.mp3',	        16),

('Wilderness',	  9, 12, '479', 'diablo2/01_wilderness.mp3',   	  1),
('Rogue',         9, 12, '180', 'diablo2/02_rogue.mp3', 	        2),
('Sisters', 		  9, 12, '105', 'diablo2/03_sisters.mp3',	        3),
('Spider', 	  	  9, 12, '319', 'diablo2/04_spider.mp3',	        4),
('Jungle', 		    9, 12, '167', 'diablo2/05_jungle.mp3',	        5),
('Zakarum', 		  9, 12, '176', 'diablo2/06_zakarum.mp3',	        6),
('Desert', 		    9, 12, '219', 'diablo2/07_desert.mp3',	        7),
('Toru', 		  	  9, 12, '177', 'diablo2/08_toru.mp3',	          8),
('Sanctuary', 	  9, 12, '248', 'diablo2/09_sanctuary.mp3',	      9),
('Crypt',         9, 12, '212', 'diablo2/10_crypt.mp3',	          10),
('Tombs',	        9, 12, '317', 'diablo2/01_tombs.mp3',   	      11),
('Monastery',     9, 12, '356', 'diablo2/02_monastery.mp3',       12),
('Cave', 		      9, 12, '231', 'diablo2/03_cave.mp3',	          13),
('Mesa', 	  	    9, 12, '206', 'diablo2/04_mesa.mp3',	          14),
('Leoric', 		    9, 12, '206', 'diablo2/05_leoric.mp3',	        15),
('Coda', 		      9, 12, '57', 'diablo2/06_coda.mp3',	            16),
('Roger & Me', 	  9, 12, '63', 'diablo2/07_roger_me.mp3',	        17)
;
