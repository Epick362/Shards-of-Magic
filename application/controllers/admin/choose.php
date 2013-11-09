<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Choose extends MY_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	function index() {
		$this->content = 'hahahahah';

		$this->template->ingame('admin/generators/choose', $this);
	}

	function items() {
		$this->load->library(array('probability', 'form_validation'));
		$this->load->helper('form_helper');

		$item_bonuses = array(
			1 => array(0 => "of the Bandit", 1 => "dex", 2 => "luc", 3 => array(1 => 2, 2 => 3, 3 => 5)),
			2 => array(0 => "of the Warrior", 1 => "str", 2 => "luc", 3 => array(1 => 1, 2 => 2, 3 => 3)),
			3 => array(0 => "of the Witch", 1 => "int", 2 => "luc", 3 => array(1 => 2, 2 => 4, 3 => 5)),
			4 => array(0 => "of the Monkey", 1 => "dex", 2 => "int", 3 => array(1 => 1, 2 => 3, 3 => 5)),

			5 => array(0 => "of the Force", 1 => "str", 3 => array(1 => 1, 2 => 2, 3 => 3)),
			6 => array(0 => "of the Assassin", 1 => "dex", 3 => array(1 => 2, 2 => 3, 3 => 5)),
			7 => array(0 => "of the Power", 1 => "int" ,3 => array(1 => 2, 2 => 4, 3 => 5)),

			8 => array(0 => "of the Ambusher", 1 => "dex", 2 => "str", 3 => array(1 => 1, 2 => 2, 3 => 3))
		);

		$first_names = array("Riverpaw", "Gnoll", "Madwolf", "Support", "Forest", "Deputy",  "Sparkmetal", "Black", "Bridgeworker\'s", "Wolfmane", "Smith\'s", "Ghoul", "Fingerbone", "Buckskin", "Frontier", "Blackrock", "Bluegill", "Harvester\'s", "Skullsplitter", "Engineering", "Drake-scale", "Lucky", "Silk-threaded", "Stonemason", "Goblin", "Metalworking", "Woodworking", "Dragonmaw", "Chief", "Pressed", "Glowing", "Ambassador\'s", "Scholarly", "Dusty", "Tunneler\'s", "Nightwalker", "Desperado", "Sapper\'s", "Embossed", "Malleable", "Sacrificial", "Barbaric", "Woolen", "Augural", "Nimar\'s", "Thinking", "Settler\'s", "Stretched", "Runed", "Rough", "Patterned", "Silvered", "Wendigo", "Cloak", "Darkshire", "Mariner", "Night", "First", "Journeyman\'s", "Burnt", "Warrior\'s", "Spellbinder", "Hunting", "Veteran", "Seer\'s", "Inscribed", "Burnished", "Brood", "Feathered", "Noble\'s", "Glyphed", "Lambent", "Humbert\'s", "Bright", "Smoldering", "Ironheart", "Deepwood", "Foreman", "Tarantula", "Cutthroat", "Battle", "Tribal", "Ancestral", "Brackwater", "Ceremonial", "Ghostly", "Dargol\'s", "Gauntlets", "Captain", "Ringed", "Guardsman", "Bone-studded", "Mystic", "Stamped", "Rugged", "Gemmed", "Dread", "Resilient", "Seafarer\'s", "Beerstained", "Raptorbane", "Vicar\'s", "Acidproof", "Camouflaged", "Stomping", "Meditative", "Ribbed", "Mercenary", "Shepherd\'s", "Crusader", "Insulated", "Mantis", "Brigand\'s", "Golden", "Polished", "Monogrammed", "Silver-thread", "Aurora", "Mistscape", "Emblazoned", "Insignia", "Imperial", "Glimmering", "Blackforge", "Tiger", "Panther", "Excelsior", "Medicine", "Darktide", "Scorching", "Poobah\'s", "Raptor", "Frost", "Darkspear", "Blackwater", "Junglewalker", "Guardian", "Handstitched", "Soft-soled", "Phoenix", "Azure", "Spider", "Enchanter\'s", "Flying", "Craftsman\'s", "Scarecrow", "Jewel-encrusted", "Beaded", "Trouncing", "Bonefist", "Beastwalker", "Iridescent", "Swampland", "Steel-clasped", "Radiant", "Grimsteel", "Ironheel", "Walking", "Skeletal", "Combat", "Stromgarde", "Arcane", "Doomsayer\'s", "Coppercloth", "Adept\'s", "Harvest", "Whispering", "Solstice", "Brimstone", "Sylvan", "Fiery", "Antiquated", "Mighty", "Boulder", "Legionnaire\'s", "Wizard\'s", "Nightwind", "Dreamer\'s", "Saber", "Stalking", "Glorious", "Elite", "Sleek", "Mistspray", "Enchanted", "Scorched", "Artisan\'s", "Vibrant", "Binding", "Buckled", "Riveted", "Timberland", "Barkshell", "Padded", "Greasy", "Cinched", "Wayfaring", "Spore-covered", "Barkeeper\'s", "Beastmaster\'s", "Banshee", "Brambleweed", "Pearl-clasped", "Living", "Steadfast", "Gustweald", "Vagabond", "Circlet", "Hammerfist", "Windfelt", "Webwing", "Ruffled", "Thick", "Snapbrook", "Faerie", "Regent\'s", "Ruffian", "Wandering", "Slarkskin", "Kimbra", "Disciple\'s", "Pioneer", "Sacred", "Grizzled", "Infantry", "Deviate", "Grassland", "Slick", "Dagmire", "Firewalker", "Violet", "Harlequin", "Willow", "Soldier\'s", "Bard\'s", "Shimmering", "Defender", "Scouting", "Battleforge", "Dervish", "Sage\'s", "Raven\'s", "Scarab", "Voodoo", "Hexed", "Dredge", "Engineer\'s", "Draftsman", "Juggernaut", "Tempered", "Moonglow", "Windborne", "Chestplate", "Beastial", "Razzeric\'s", "Ironforge", "Gnomish", "Dryleaf", "Swiftrunner", "Enforcer", "Mourning", "Lancer", "Gelkis", "Lilac", "Braced", "Magram", "Hellion", "Sanguine", "Auric", "Stormfire", "Baroque", "Nimbus", "Heartwood", "Beetle", "Prelacy", "Truefaith", "Earthen", "Crimson", "Zodiac", "Brutal", "Explorer\'s", "Light", "Nimble", "Regal", "Efflorescent", "Grizzly", "Wildwood", "Fletcher\'s", "Herbalist\'s", "Elder\'s", "Pilferer\'s", "Dusky", "Swift", "Infiltrator", "Phalanx", "Twilight", "Sentinel", "Knight\'s", "Ranger", "Captain\'s", "Manaweave", "Astral", "Nether-lace", "Gossamer", "Cabalist", "Champion\'s", "Polar", "Evergreen", "Mithril", "Ornate", "Ninja", "Steel", "Hibernal", "Heraldic", "Chromite", "Jouster\'s", "Comfortable", "Nightscape", "Turtle", "Tough", "Serpentskin", "Ebonhold", "Valorous", "Traveler\'s", "Alabaster", "Renegade", "Field", "Tromping", "Mud\'s", "Durtfeet", "Gemshale", "Fire-welded", "Fairywing", "Sleeping", "Barkmail", "Repairman\'s", "Shilly", "Pratt\'s", "Jangdor\'s", "Skilled", "Master", "Swashbuckler", "Shinkicker", "Chelonian", "Failed", "Chainlink", "Speedy", "Granite", "Vinehedge", "Stargazer", "Rushridge", "Dawnrider\'s", "Sentinel\'s", "Wingcrest", "Stronghorn", "Leather", "Grappler\'s", "Garrison", "Rustler", "Tharg\'s", "Simple", "Gypsy", "Cadet", "Greenweave", "Bandit", "Raider\'s", "Ivycloth", "Superior", "Fortified", "Durable", "Scaled", "Banded", "Conjurer\'s", "Archer\'s", "Sorcerer", "Huntsman\'s", "Jazeraint", "Royal", "Tracker\'s", "Brigade", "Abjurer\'s", "Chieftain\'s", "Warmonger\'s", "Shadoweave", "Admiral\'s", "Cindercloth", "Pious", "Colorful", "Duskwoven", "Righteous", "Gothic", "Councillor\'s", "Wanderer\'s", "Revenant", "Templar", "Mystical", "Swashbuckler\'s", "Crusader\'s", "Overlord\'s", "Engraved", "Emerald", "Scarlet", "Duracin", "Everlast", "Blackened", "Shadowy", "Bright-Eye", "Catseye", "Deepdive", "Parachute", "Rancher\'s", "Brewer\'s", "Trailblazer", "Jutebraid", "Talbar", "Quagmire", "Encarmine", "Enormous", "Firwillow", "Nightscale", "Steelsmith", "Centurion", "Lordrec", "Dragonflight", "Drakefire", "Kaylari", "Runesteel", "Teacher\'s", "Wanderlust", "Hakkari", "Jackseed", "Sower\'s", "Surveyor\'s", "Apothecary", "Blazewind", "Prismscale", "Warforged", "Brightscale", "Bloodband", "Loreskin", "Rambling", "Battlehard", "Rancor", "Maddening", "Snarkshaw", "Eschewal", "Ethereal", "Clouddrift", "Breezecloud", "Plainstalker", "Outrider", "Moonlit", "Quintis\'", "Archaeologist\'s", "Excavator\'s", "Bejeweled", "Treetop", "Clayridge", "Shizzle\'s", "Grotslab", "Cragplate", "Relic", "Penance", "Conservator", "Shieldplate", "Windshear", "Splintsteel", "Hazecover", "Brazen", "Shaleskin", "Wyrmhide", "Valconian", "Brindlethorn", "Boulderskin", "Whispersilk", "Basaltscale", "Raincaster", "Lavaplate", "Crypt", "Sunborne", "Nightfall", "Stalwart", "Bloodsail", "Brilliant", "Thorium", "Bingles\'", "Runecloth", "Frostweave", "Mystic\'s", "Native", "Brightcloth", "Felcloth", "Aboriginal", "Ritual", "Wizardweave", "Ghostweave", "Pagan", "Buccaneer\'s", "Watcher\'s", "Raincaller", "Thistlefur", "Vital", "Geomancer\'s", "Embersilk", "Darkmist", "Lunar", "Bloodwoven", "Gaea\'s", "Opulent", "Arachnidian", "Bonecaster\'s", "Celestial", "Resplendent", "Stonecloth", "Silksand", "Windchaser", "Venomshroud", "Highborne", "Prospector\'s", "Bristlebark", "Dokebi", "Hawkeye\'s", "Warden\'s", "Scorpashi", "Keeper\'s", "Pridelord", "Hulking", "Slayer\'s", "Enduring", "Ravager\'s", "Khan\'s", "Protector", "Bloodlust", "Symbolic", "Tyrant\'s", "Sunscale", "Vanguard", "Saltstone", "Brutish", "Lofty", "Warbringer\'s", "Bloodforged", "Primal", "Lupine", "Volcanic", "Frostsaber", "Chimeric", "Wicked", "Runic", "Rigid", "Robust", "Cutthroat\'s", "Ghostwalker", "Nocturnal", "Imposing", "Potent", "Praetorian", "Grand", "Wildkeeper", "Guststorm", "Feral", "Wrangler\'s", "Pathfinder", "Headhunter\'s", "Trickster\'s", "Rageclaw", "Jadefire", "Dryweed", "Ridgeback", "Breakwater", "Shucking", "Crustacean", "Peerless", "Ghastly", "Dredgemire", "Gargoyle", "Featherbead", "Savannah", "Dustfall", "Lightstep", "Desert", "Tundra", "Grimtoll", "Lightheel", "Loamflake", "Palestrider", "Windsong", "Plainsguard", "Brawnhide", "Charger\'s", "Bloodspattered", "Outrunner\'s", "Grunt\'s", "Spiked", "Sentry\'s", "Pillager\'s", "Marauder\'s", "Sparkleshell", "Pardoc", "Ringtail", "Bracesteel", "Ancient", "Bonelink", "Gryphon", "Formidable", "Ironhide", "Merciless", "Impenetrable", "Wrangling", "Chemist\'s", "Brantwood", "Blight", "Gearforge", "Crystal", "Fernpulse", "Turquoise", "Seaspray", "Shining", "Cerise", "Orchid", "Hameya\'s", "Shadowskin", "Bricksteel", "Astoria", "Traphook", "Jadescale", "Freewind", "Seapost", "Blinkstrike", "Swiftfoot", "Condor", "Friar\'s", "Acolyte\'s", "Aquarius", "Wildhunter", "Deftkin", "Witherseed", "Rugwood", "Shredder", "Oilrag", "Silkstream", "Arcmetal", "Gripsteel", "Braidfur", "Owlbeard", "Windseeker", "Sandspire", "Screecher", "Spritekin", "Duskwing", "Boorguard", "Cobalt", "Zealot\'s", "Luminescent", "Smokey\'s", "Jungle", "Chestnut", "Branchclaw", "Acumen", "Sprightring", "Relentless", "Sagebrush", "Hulkstone", "Lionfur", "Zorbin\'s", "Greenleaf", "Laquered", "Owlbeast", "Everwarm", "Slagplate", "Seared", "Charred", "Inquisitor\'s", "Branded", "Southsea", "Undercity", "Earth", "Ursa\'s", "Suncrown", "Apothecary\'s", "Deathstalker\'s", "Apprentice", "Bogwalker", "Volunteer\'s", "Ghostclaw", "Fallen", "Sylastor\'s", "Salvaged", "Farstrider\'s", "Sentry", "Supple", "Troll", "Mirren\'s", "Ravager", "Kurken", "Kurkenstoks", "Cowlen\'s", "Vandril\'s", "Tunic", "Savage", "Defender\'s", "Wastewalker\'s", "Venn\'ren\'s", "Demonslayer\'s", "Windtalker\'s", "Lightbearer\'s", "Flamehandler\'s", "Pilgrim\'s", "Vindicator\'s", "Omenai", "Thunderforge", "Clefthoof", "Ikeyen\'s", "Marshstrider\'s", "Windcaller\'s", "Cenarion", "Zangar", "Marsh", "Oversized", "Ango\'rosh", "Lo\'ap\'s", "Muck-ridden", "Spaulders", "Judicator\'s", "Warcaster\'s", "Murkblood", "Melia\'s", "Greenkeeper\'s", "Thunderbringer\'s", "Smuggler\'s", "Greenblood", "Caustic", "Eighty", "Windroc", "Murk-Darkened", "Daggerfen", "Feralfen", "Tim\'s", "Telaar", "Serpent", "Bracers", "Talbuk", "Manacles", "Warmaul", "Segmented", "Sunstrider", "Fearless", "Fierce", "Swamprunner\'s", "Terrorcloth", "Consortium", "Fleet", "Eagle", "Talonstalker", "Arakkoa", "Flintlocke\'s", "Aerodynamic", "Dirigible", "Miner\'s", "Farmhand\'s", "Jessera\'s", "Crystal-Flecked", "Scholar\'s", "Flutterer", "Cryo-Core", "Kessel\'s", "Cincture", "Venomous", "Elekk", "Shard-Covered", "Researcher\'s", "Technician\'s", "Ornately", "Crystal-Studded", "Protective", "Corin\'s", "Lightweight", "Circle\'s", "Refuge", "Expedition", "Aldor", "Hearty", "Cushy", "Infiltrator\'s", "Crazy", "Moonstruck", "Ranger\'s", "Rusted", "Courier\'s", "Tranquillien", "Bronze", "Renzithen\'s", "Rotting", "Batskin", "Undertaker\'s", "An\'telas", "Maltendis\'s", "Abyssal", "Kirin\'Var", "Battle-Mage\'s", "Harmony\'s", "Andrethan\'s", "Reinforced", "Dawnstrike\'s", "Cowpoke\'s", "Dragon", "Goldenlink", "Blued", "Shadowbrim", "Raging", "Nature-Stitched", "Phantasmal", "Skyfire", "Flayer-Hide", "Veteran\'s", "Scale", "Protectorate", "Magistrate\'s", "Invader\'s", "Spiritbinder\'s", "Spiritualist\'s", "Nether", "Sparky\'s", "Netherstorm", "Midrealm", "After", "Mixologist\'s", "Doc\'s", "Boot\'s", "Landing", "Overmaster\'s", "Junior", "Zephyrion\'s", "Chestguard",  "Heavy-Duty", "Zaxxis", "Scavenged", "Exotic", "Flesh", "Starcaller\'s", "Diviner\'s", "Ferocious", "Druidic", "Surger\'s", "Demolisher\'s", "Energized", "Warp-Shielded", "Kaylaan\'s", "Brightdawn", "Bloodguard\'s", "Nightstalker\'s", "Thadell\'s", "Farahlite", "Netherfarer\'s", "Warpthread", "Gold-Trimmed", "Warpweaver\'s", "Underworld", "Ashwalker\'s", "Earthmender\'s", "Sketh\'lon", "Soothsayer\'s", "Grips", "Eva\'s", "Azurestrike", "Manimal\'s", "Mooncrest", "Darkhunter\'s", "Oronok\'s", "Torn-heart", "Ash-Covered", "Skybreaker\'s", "Singed", "Uvuros", "Silvermoon", "Sinister", "Nether-Rocket", "Rocket-Chief", "Sylvanaar", "Protector\'s", "Commander", "Spelunker\'s", "All-Weather", "Gurn\'s", "Fizit\'s", "Precise", "Devolved", "Clocktock\'s", "Metro\'s", "Nickwinkle\'s", "Party", "Charged", "Scalewing", "Muscle", "Toshley\'s", "Razaani-Buster", "Witch", "T\'chali\'s", "Hexxer\'s", "Tor\'chunk\'s", "Thunderlord", "Gor\'drek\'s", "Bear-Strength", "Coven", "Wyrmcultist\'s", "Hewing", "Chest", "Blackwhelp", "Dragonkin", "Whelpscale", "Inkling\'s", "Blackwing", "Wraithcloth", "Diluvian", "Whiteknuckle", "Darktread", "Chaintwine", "Fairweather\'s", "Chemise", "Scout\'s", "Leesa\'oh\'s", "Dreadwing", "Netherhide", "Mok\'Nathal", "Spiritcaller\'s", "Ritualist\'s", "Cilice", "Stillfire", "Redeemer\'s", "Skywitch", "Warpstalker", "Bloodfire", "Blacksting", "Marshfang", "Evergrove", "Ruuan", "Death-Speaker\'s", "Dragonbone", "Noble", "Drake", "Ascendant\'s", "Illidari", "Legguards", "Horns", "Spellbound", "Grimsby\'s", "Destroyer\'s", "Skirmisher\'s", "Brogg\'s", "Cobalt-threaded", "Mordant\'s", "Rustproof", "Crested", "Grimtotem", "Oiled", "Marshwarden\'s", "Raptorhide", "Steel-banded", "Refitted", "Gleaming");
		$queries = 0;
		$weapon_subclass_names = array( 1 => "axe", 2 => "mace", 3 => "sword", 4 => "staff", 5 => "dagger", 6 => "bow" );
		$subclass_names = array( 1 => "cloth", 2 => "leather", 3 => "plate" );
		$equip_slots = array( 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11 );
		$slot_names = array( 1, 2, 3 => "head", 4 => "shoulders", 5 => "back", 6 => "chest", 7 => "hands", 8 => "waist", 9 => "legs", 10 => "feet", 11 => "neck" );
		$weapon_speeds = array( 1 => 2300, 2 => 2000, 3 => 1800, 4 => 2300, 5 => 1400, 6 => 1800 );
		$stats_in_slots = array( 1 => 0.7, 2 => 0.6, 3 => 1.0, 4 => 0.9, 5 => 0.65, 6 => 1.0, 7 => 0.8, 8 => 0.75, 9 => 1.0, 10 => 0.9, 11 => 0.65 );
		$subclass_equip_names = array(
			1 => array( 
				1 => array( "Cleaver", "Axe", "Handaxe"), // AXE
				2 => array( "Mace", "Hammer", "Club" ), // MACE 
				3 => array( "Sword", "Blade", "Slicer" ), // SWORD
				4 => array( "Staff", "Greatstaff", "Warstaff" ), // STAFF
				5 => array( "Dagger", "Slicer", "Shanker" ), // DAGGER
				6 => array( "Bow", "Longbow", "Shortbow" ) // BOW
			),
			3 => array( 
				1 => array( "Cape", "Hood", "Cowl" ),
				2 => array( "Mask", "Helm", "Headpiece" ),
				3 => array( "Helmet", "Faceplate", "Faceguard" ) 
			),
			4 => array( 
				1 => array( "Mantle", "Pads", "Amice" ),
				2 => array( "Splauders", "Mantle", "Pauldrons" ),
				3 => array( "Pauldrons", "Shoulder Pads", "Shoulders" ) 
			),
			5 => array( 
				1 => array( "Cape", "Drape", "Cloak" ),
				2 => array( "Cape", "Drape", "Cloak" ),
				3 => array( "Cape", "Drape", "Cloak" ) 
			),
			6 => array( 
				1 => array( "Robe", "Vest", "Wraps" ),
				2 => array( "Tunic", "Jerkin", "Vest" ),
				3 => array( "Breastplate", "Armor", "Chestpiece" ) 
			),
			7 => array( 
				1 => array( "Handwraps", "Gloves", "Handgrips" ),
				2 => array( "Gloves", "Handwraps", "Gauntlets" ),
				3 => array( "Gauntlets", "Plate Gloves", "Handguards" ) 
			),
			8 => array( 
				1 => array( "Girdle", "Cord", "Belt" ),
				2 => array( "Waistband", "Belt", "Sash" ),
				3 => array( "Girdle", "Waistguard", "Waistplate" ) 
			),
			9 => array( 
				1 => array( "Pants", "Trousers", "Leggings" ),
				2 => array( "Legguards", "Pants", "Britches" ),
				3 => array( "Legplates", "Legguards", "Pants" ) 
			),
			10 => array( 
				1 => array( "Boots", "Slippers", "Footwraps" ),
				2 => array( "Boots", "Footpads", "Shoes" ),
				3 => array( "Boots", "Greaves", "Sabatons" ) 
			),
			11 => array( 
				1 => array( "Choker", "Pendant", "Necklace" ),
				2 => array( "Choker", "Pendant", "Necklace" ),
				3 => array( "Choker", "Pendant", "Necklace" ) 
			)
		);

		$this->form_validation->set_rules('send', 'Send', 'required');

		if($this->form_validation->run()) {
			$output = "";
			$count = 240;
			for ($i = 1; $i <= $count; $i++) {
				$level = floor(($i + 5) / 6);
				$armor_subclass = rand(1, 3);

				if($armor_subclass == 1) {
					$this->probability->addEvent( 3, 45 );
					$this->probability->addEvent( 7, 45 );
					$this->probability->addEvent( 4, 10 );
				}elseif($armor_subclass == 2){
					$this->probability->addEvent( 1, 35 );
					$this->probability->addEvent( 4, 35 );
					$this->probability->addEvent( 6, 15 );
					$this->probability->addEvent( 8, 15 );
				}elseif($armor_subclass == 3){
					$this->probability->addEvent( 2, 25 );
					$this->probability->addEvent( 3, 25 );
					$this->probability->addEvent( 5, 20 );
					$this->probability->addEvent( 7, 15 );
					$this->probability->addEvent( 8, 15 );
				}
				$bonus = $this->probability->randomEvent(1);

				if($level < 10) {
					$rand = rand(1, 6);
				}else{
					$rand = rand(1, 8);
				}
				$rand_name = rand(1, count($first_names) - 1);
				if(!array_key_exists($rand_name, $first_names)) {
					$rand_name = rand(1, count($first_names) - 1);
					continue;
				}
				$rand_image_set = rand(1, 3);
				$rand_image_weap = rand(1, 5);
				foreach($equip_slots as $slot) {
					$armor = round((32 + ($level * mt_rand(3, 5)) + rand( 1, $level * 2 ) / 2) * $stats_in_slots[$slot] );
					if(count($item_bonuses[$bonus]) == 4) {
						$stamina_multiplier = 1.2;
						$division = 3;
					}else{
						$stamina_multiplier = 0.6;
						$division = 2;
					}
					$stats[1] = round((($level * 1.6 + rand(0, 3)) / 4 ) * $stats_in_slots[$slot] );
					$stats[2] = round((($level * 1.6 + rand(0, 3)) / 4 ) * $stats_in_slots[$slot] );
					$stats[3] = round((($level * 1.6 + rand(0, 3)) / 4 ) * $stats_in_slots[$slot] * $stamina_multiplier );	
					if($slot == 1 || $slot == 2) {
						$subclass_rand = rand(1, 3);
						$subclass = $item_bonuses[$rand][3][$subclass_rand];
						$speed = $weapon_speeds[$subclass];
						$rand_sec_name = rand(0, 2);
						$second_name = $subclass_equip_names[1][$subclass][$rand_sec_name];
						$min_damage = round(($level - mt_rand($level/4, $level/2) + rand(1, 3)) * ($speed / 1000));
						$max_damage = round(($level + mt_rand($level/4, $level/2) + rand(3, 4)) * ($speed / 1000));
						$image_path = 'inv_'.$weapon_subclass_names[$subclass].'_0'.$rand_image_set.'.jpg';
						$cost = ($level * 3000) + rand( 0, $level * 1500 / 2 );
						if(count($item_bonuses[$bonus]) == 4) {
							$output.="INSERT INTO `item_template` (`name`, `RequiredLevel`, `quality`, `class`, `subclass`, `image_path`, `cost`, `equip_slot`, `min_damage`, `max_damage`, `speed`, `".$item_bonuses[$bonus][1]."`, `".$item_bonuses[$bonus][2]."`, `sta`, `generated`) VALUES ('".$first_names[$rand_name]." ".$second_name." ".$item_bonuses[$bonus][0]."', ".$level.", 2, 2, ".$subclass.", '".$image_path."', ".$cost.", ".$slot.", ".$min_damage.", ".$max_damage.", ".$speed.", ".$stats[1].", ".$stats[2].", ".$stats[3].", 1);<br />";			
						}else{
							$output.="INSERT INTO `item_template` (`name`, `RequiredLevel`, `quality`, `class`, `subclass`, `image_path`, `cost`, `equip_slot`, `min_damage`, `max_damage`, `speed`, `".$item_bonuses[$bonus][1]."`, `sta`, `generated`) VALUES ('".$first_names[$rand_name]." ".$second_name." ".$item_bonuses[$bonus][0]."', ".$level.", 2, 2, ".$subclass.", '".$image_path."', ".$cost.", ".$slot.", ".$min_damage.", ".$max_damage.", ".$speed.", ".$stats[1].", ".$stats[3].", 1);<br />";			
						}
					}else{
						$rand_sec_name = rand(0, 2);
						$second_name = $subclass_equip_names[$slot][$armor_subclass][$rand_sec_name];
						if($slot == 11 || $slot == 5) {
							$temp_armor_subclass = 0;
						}else{
							$temp_armor_subclass = $armor_subclass;
						}

						if( $temp_armor_subclass ) {
							$image_path = 'inv_'.$subclass_names[$armor_subclass].'_'.$slot_names[$slot].'_0'.$rand_image_set.'.jpg';
						}else{
							$image_path = 'inv_'.$slot_names[$slot].'_0'.$rand_image_set.'.jpg';
						}

						$cost = ($level * 1100) + rand( 0, $level * 1500 / 2 );
						if($level < 15 && $slot == 4) {
							$output .= "-- Head skipped ( LEVEL < 15 )<br />";
						}elseif($level < 10 && ($slot == 4 || $slot == 5 || $slot == 8 || $slot == 11)) {
							$output .= "-- Slot n".$slot." skipped ( LEVEL < 10 )<br />";
						}else{
							if(count($item_bonuses[$bonus]) == 4) {
								$output.="INSERT INTO `item_template` (`name`, `RequiredLevel`, `quality`, `class`, `subclass`, `image_path`, `cost`, `equip_slot`, `armor`, `".$item_bonuses[$bonus][1]."`, `".$item_bonuses[$bonus][2]."`, `sta`, `generated`) VALUES ('".$first_names[$rand_name]." ".$second_name." ".$item_bonuses[$bonus][0]."', ".$level.", 2, 1, ".$temp_armor_subclass.", '".$image_path."', ".$cost.", ".$slot.", ".$armor.", ".$stats[1].", ".$stats[2].", ".$stats[3].", 1);<br />";			
							}else{
								$output.="INSERT INTO `item_template` (`name`, `RequiredLevel`, `quality`, `class`, `subclass`, `image_path`, `cost`, `equip_slot`, `armor`, `".$item_bonuses[$bonus][1]."`, `sta`, `generated`) VALUES ('".$first_names[$rand_name]." ".$second_name." ".$item_bonuses[$bonus][0]."', ".$level.", 2, 1, ".$temp_armor_subclass.", '".$image_path."', ".$cost.", ".$slot.", ".$armor.", ".$stats[1].", ".$stats[3].", 1);<br />";			
							}
						}			
					}
					$queries++;
				}
				unset($first_names[$rand_name]);
				$output .= "<br />";
			}
		}else{
			$output = "";
		}

		$this->content = $output;
		$this->queries = $queries.' queries.';
		$this->template->ingame('admin/generators/items', $this);
	}

	function creeps() {
		$this->load->library(array('probability', 'form_validation'));
		$this->load->helper('form_helper');

		$this->themes = array(1=>'Normal', 2=>'Icy', 3=>'Corrupted');
		$this->biomes = array(1=>'Forest', 2=>'Hills', 3=>'Swamps', 4=>'Village', 5=>'Desert', 6=>'Lake', 7=>'Meadows');
		$this->families = array(1=>'Humanoid', 2=>'Mechanical', 3=>'Beasts', 4=>'Undead');

		$this->form_validation->set_rules('send', 'Send', 'required');
		$this->form_validation->set_rules('theme', 'theme', 'required|is_numeric');
		$this->form_validation->set_rules('biome', 'biome', 'required|is_numeric');
		$this->form_validation->set_rules('level', 'Level Range', 'required|is_numeric');

		if($this->form_validation->run()) {
			$this->npc = $this->generateCreeps($this->input->post('theme'), $this->input->post('biome'), $this->input->post('family'), $this->input->post('level'));
		}else{
			$this->npc = array();
		}
		$this->template->ingame('admin/generators/creeps', $this);		
	}

	function world() {
		$this->load->library(array('probability', 'form_validation'));
		$this->load->helper('form_helper');

		$names = array( 1 => array(),
						2 => array('Snowy', 'Frozen', 'Icy', 'Frost-bitten'),
						3 => array('Shadow', 'Corrupted', 'Dark', 'Tainted'));

		$zones = array( 1 => array('Forest', 'Woods'), 
						2 => array('Mountain', 'Hill', 'Peak'), 
						3 => array('Swamp', 'Basin'), 
						4 => array('Village'),
						5 => array('Desert'), 
						6 => array('Lake', 'Pond'), 
						7 => array('Meadows', 'Plains'));

		$maps = array(  1 => array( 'names' => array(1 => 'Forest', 2 => 'Woods'), 
									'zones' => array(1 => 60, 3 => 5 , 4 => 20, 6 => 5 , 7 => 10)), 

						2 => array( 'names' => array(1 => 'Mountains', 2 => 'Hills', 3 => 'Peaks'), 
									'zones' => array(1 => 20, 2 => 40, 4 => 20, 6 => 10, 7 => 10)),

						3 => array( 'names' => array(1 => 'Swamps', 2 => 'Basin'), 
									'zones' => array(1 => 10, 3 => 75, 4 => 10, 6 => 5)), 

						4 => array( 'names' => array(1 => 'Desert'), 
									'zones' => array(4 => 15, 5 => 80, 6 => 5)),

						5 => array( 'names' => array(1 => 'Plains'), 
									'zones' => array(1 => 20, 3 => 10, 6 => 10, 7 => 60)));

		$themes = array('normal' => 50, 'icy' => 30, 'corrupted' => 20);


		$this->form_validation->set_rules('send', 'Send', 'required');
		$this->form_validation->set_rules('x', 'X', 'required|is_numeric');
		$this->form_validation->set_rules('y', 'Y', 'required|is_numeric');
		$this->form_validation->set_rules('level', 'level', 'required|is_numeric');
		$this->form_validation->set_rules('mapid', 'mapid', 'required|is_numeric');

		if($this->form_validation->run()) {
			$x = $this->input->post('x'); $y = $this->input->post('y');
			$mapid = $this->input->post('mapid');
			$this->map->x = $x; $this->map->y = $y;
			$r_maptype = mt_rand(1, count($maps));
			if(($y <= 3 || $y > 6) && $r_maptype == 4) { // Northern Zones change Desert to Forest
				$r_maptype = 1;
			}
			$r_mapname = mt_rand(1, count($maps[$r_maptype]['names']));

			$this->map->mapname = $maps[$r_maptype]['names'][$r_mapname];

			if($y < 4 || $y > 6) { // Northern Zones
				$r_mapthemename = mt_rand(0, count($names[2]) - 1);
				$this->map->mapthemename = $names[2][$r_mapthemename];
				$this->map->maptheme = 2;
			}else{ // Normal or Corrupted
				$m_rand = new probability();
				$m_rand->addEvent(1, 90);
				$m_rand->addEvent(3, 10);
				$r_maptheme = $m_rand->randomEvent();
				if($r_maptheme == 3) {
					$r_mapthemename = mt_rand(0, count($names[3]) - 1);
					$this->map->mapthemename = $names[$r_maptheme][$r_mapthemename];	
					$this->map->maptheme = 3;			
				}else{
					$this->map->mapthemename = '';
					$this->map->maptheme = 1;
				}
			}

			$this->map->mapfullname = trim($this->map->mapthemename.' '.$this->map->mapname);

			$zonetypes_count = array();
			// Creep ID
			$cn = 100;

			$sql = "DELETE FROM `maps` WHERE `x` = '".$x."' AND `y` = '".$y."'; <br />";
			$sql .= "DELETE FROM `zones` WHERE `mapid` = '".$mapid."';<br />";
			$sql .= "INSERT INTO `maps` (id, name, x, y) VALUES ('".$mapid."', '".$this->map->mapfullname."', '".$x."', '".$y."'); <br />";
			for ($i=1; $i <= 9; $i++) { 
				$rand = new probability();
				foreach($maps[$r_maptype]['zones'] as $zonetype => $chance) {
					$rand->addEvent($zonetype, $chance);
				}
				if(array_key_exists(4, $zonetypes_count) && $zonetypes_count[4] >= 2) {
					$rand->removeEvent(4);
				}

				if($this->map->maptheme > 1) {
					$r_zonethemename = mt_rand(0, count($names[$this->map->maptheme]) - 1);
					$this->map->$i->zonethemename = $names[$this->map->maptheme][$r_zonethemename];
				}else{
					$this->map->$i->zonethemename = '';
				}

				$r_zonetype = $rand->randomEvent();
				if(array_key_exists($r_zonetype, $zonetypes_count)) {
					$zonetypes_count[$r_zonetype]++;
				}else{
					$zonetypes_count[$r_zonetype] = 1;
				}
				$r_zonename = mt_rand(0, count($zones[$r_zonetype]) - 1);


				$this->map->$i->zonename = $zones[$r_zonetype][$r_zonename];
				$this->map->$i->zonefullname = trim($this->map->$i->zonethemename.' '.$this->map->$i->zonename);

				if($r_zonetype == 4) {
					$is_city = 1;
				}else{
					$is_city = 0;
				}

				if($i - 3 <= 0) { // Y is 0
					$zoneY = 0;
					$zoneX = $i - 1;
				}elseif($i - 6 <= 0 ) {
					$zoneY = 1;
					$zoneX = $i - 4;
				}elseif($i - 9 <= 0 ) {
					$zoneY = 2;
					$zoneX = $i - 7;
				}

				$sql .= "INSERT INTO `zones` (x, y, mapid, name, is_city) VALUES ('".$zoneX."', '".$zoneY."', '".$mapid."', '".$this->map->$i->zonefullname."', '".$is_city."'); <br />";

				if($r_zonetype != 6) { // NO MOBS IN LAKES 
					$this->map->$i->creeps = $this->generateCreeps($this->map->maptheme, $r_zonetype, NULL, $this->input->post('level'));

					foreach($this->map->$i->creeps as $species) {
						foreach($species as $key => $creep) {
							if(is_numeric($key)) {
								$sql .= "INSERT INTO `creature_template` (";
								foreach($creep as $pkey => $property) {
									if ($this->last($creep, $pkey)) {
										$sql .= $pkey;
									}else{
										$sql .= $pkey.", ";
									}
								}
								$sql .=	") VALUES (";
								foreach($creep as $pkey => $property) {
									if ($this->last($creep, $pkey)) {
										$sql .= "'".$property."'";
									}else{
										$sql .= "'".$property."', ";
									}
								}
								$sql .= ");<br />";
								$sql .= "INSERT INTO `creature_locations` (id, map, zone) VALUES ('".$cn."', '".$mapid."', '".$i."'); <br />";

								$cn++;
							}
						}
					}
				}
				$sql .= "<br /><br />";
			}

			$this->sql = $sql;
		}else{
			$this->map = array();
			$this->sql = '';
		}

		$this->content = 'Here is your map';
		$this->template->ingame('admin/generators/world', $this);
	}

	function last($array, $key) {
	    end($array);
	    return $key === key($array);
	}

	function generateCreeps($theme, $biome, $r_family = NULL, $level_range = NULL) {
		$this->load->helper('file');
		$s1 = array(1 => 'Human', 2 => 'Goblin', 3 => 'Elf', 4 => 'Troll');
		$s2 = array(1 => 'Mechanical');
		$s3 = array(1=>'Wolf', 2=>'Boar', 3=>'Bear', 4=>'Deer', 5=>'Ent', 6=>'Spider', 7=>'Dragon', 8=>'Tiger', 9=>'Gorilla', 10=>'Fox', 11=>'Turtle', 12=>'Penguin');
		$s4 = array(1 => 'Ghost', 2 => 'Wraith', 3 => 'Forsaken');

		$biome_family = array(1 => array(1 => 15, 2 => 5 , 3 => 80),	//
							  2 => array(1 => 10, 2 => 5 , 3 => 85),	//
							  3 => array(1 => 5 , 2 => 5 , 3 => 90),	//				DOESN'T WORK
							  4 => array(1 => 100),						//
							  5 => array(1 => 50, 3 => 50),				//
							  7 => array(1 => 20, 2 => 0 , 3 => 80));	//

		$family = array(1 => array( 1 => 60, 2 => 10, 3 => 20, 4 => 10),
						2 => array( 1 => 100),
						3 => array( 1 => array( 1 => array(1 => 20, 2 => 5 , 3 => 15, 4 => 10, 5 => 15, 6 => 5 , 7 => 10, 8 => 5, 9 => 5, 10=> 10),
												2 => array(1 => 30, 3 => 25, 6 => 10, 7 => 15, 10=> 10, 12=> 10),
												3 => array(1 => 20, 2 => 5 , 3 => 15, 5 => 15, 6 => 25, 7 => 20)),
									2 => array( 1 => array(1 => 15, 2 => 10, 3 => 15, 4 => 10, 5 => 10, 6 => 10, 7 => 30),
												2 => array(1 => 15, 2 => 10, 3 => 15, 6 => 20, 7 => 40),
												3 => array(1 => 15, 2 => 10, 3 => 15, 6 => 20, 7 => 40)),
									3 => array( 1 => array(2 => 5 , 3 => 5 , 5 => 40, 6 => 30, 11=> 20),
												2 => array(2 => 15 , 3 => 25 , 5 => 30, 6 => 30),
												3 => array()),
									// 4 is Village
									5 => array( 1 => array(1 => 10, 6 => 30, 7 => 30, 11 => 30),
												2 => array(), // Never ice in Desert
												3 => array()),
									// 6 is Water
									7 => array( 1 => array(1 => 20, 2 => 10, 3 => 10, 4 => 10, 7 => 20, 8 => 20, 10 => 10),
												2 => array(1 => 30, 2 => 20, 3 => 30, 7 => 10, 10 => 10),
												3 => array())),
										// TO ADD MORE BIOMES FOR BEASTS
						4 => array( 1 => 33, 2 => 33, 3 => 34));

		$adjective = array( 1 => array(),
							2 => array(),
							3 => array('Frenzied', 'Corrupted', 'Enraged', 'Tainted'));

		$types  = mt_rand(2, 3);

		$p_family = new probability();
		for ($o = 1; $o <= $types; $o++) {
			if(!$r_family) {
				if($theme == 3) { // Undead only on undead theme
					$r_family = 4;
				}else{
					foreach($biome_family[$biome] as $b_family => $b_chance) {			//
						$p_family->addEvent($b_family, $b_chance);						//			DOESN'T WORK
					} 																	//
					$r_family = $p_family->randomEvent();								//
				}			
			}

			$rand = new probability();
			if($r_family == 3) {// BEASTS
				switch($theme) {
					case 1: $faction = 2; break;
					case 2: $faction = 2; break;
					case 3: $faction = 3; break;
				}
				foreach($family[$r_family][$biome][$theme] as $species_number => $chance) {
					$rand->addEvent($species_number, $chance);
				} 
			}else{ // ELSE
				switch($r_family) {
					case 1: $faction = 1; break;
					case 2: $faction = 2; break;
					case 4: $faction = 3; break;
				}
				foreach($family[$r_family] as $species_number => $chance) {
					$rand->addEvent($species_number, $chance);
				} 
			}
			$r_species = $rand->randomEvent();
			$temp_var = 's'.$r_family;
			$data->$o->speciesname = ${$temp_var}[$r_species];

			$amount = mt_rand(2, 4);
			for ($i = 1; $i <= $amount; $i++) { 
				if($r_family <= 2) {
					$data->$o->$i->name = $this->generateName();
					$data->$o->$i->subname = $data->$o->speciesname;
					$rank = 0;
					$data->$o->$i->class = mt_rand(1, 3);
				}elseif($r_family == 3){
					switch($i) {
						case 1: $prefix = 'Young'; 	$rank = 0; break;
						case 2: $prefix = ''; 		$rank = 0; break;
						case 3: $prefix = 'Elder'; 	$rank = 0; break;
						case 4: $prefix = ''; 		$rank = 1; break;
					}
					$data->$o->$i->name = trim($prefix.' '.$data->$o->speciesname);
					$data->$o->$i->class = 3;
				}else{
					$r_adjective = mt_rand(0, count($adjective[3]) - 1);
					$prefix = $adjective[3][$r_adjective];
					$rank = 1;
					$data->$o->$i->name = trim($prefix.' '.$data->$o->speciesname);
					$data->$o->$i->class = mt_rand(1, 3);
				}
				$data->$o->$i->rank = $rank;
				$data->$o->$i->faction = $faction;
				$data->$o->$i->MinLevel = mt_rand($level_range - 2, $level_range) + $data->$o->$i->rank; // Level in range plus +1 for elites
				$data->$o->$i->MaxLevel = mt_rand($level_range, $level_range + 2) + $data->$o->$i->rank; // Level in range plus +1 for elites

				$data->$o->$i->modHealth = 1 + $rank;
				$data->$o->$i->modMana = 1 + $rank;

				$data->$o->$i->min_damage = 10;
				$data->$o->$i->max_damage = 15;

				$data->$o->$i->armor = 20;
				$data->$o->$i->flags = 0;
			}
		}

		return $data;
	}

	function generateName() {
		$this->load->helper('file');
		$filedata = read_file('assets/data/samplenames');
		$filedata = explode("\n", $filedata); // find how to explode at new line

		$rand = mt_rand(1, count($filedata) - 1);

		return $filedata[$rand];
	}
}