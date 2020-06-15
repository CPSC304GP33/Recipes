CREATE TABLE Instruction(
InsID INTEGER AUTO_INCREMENT PRIMARY KEY,
Instructions VARCHAR(8000) UNIQUE NOT NULL,
ServingSize INTEGER
);

INSERT INTO Instruction VALUES (1,'Mix ingredients into a pan then put it into the oven.', 8);
INSERT INTO Instruction VALUES (2,'Mix ingredients in a bowl. Separate into even balls. Put balls on baking sheet then place it into the oven.', 20);
INSERT INTO Instruction VALUES (3,'Add the dry ingredients to the mug and stir. Add the wet ingredients and stir. Put all in the microwave on high until the mixture is cooked which takes about 1 minute 40 seconds for a 1000 watt microwave. Top with ice cream.', 1);
INSERT INTO Instruction VALUES (4,'Cook sauce. Cook lasagna noodles. Spread layers of noodle, cheese and sauce into the oven-safe pan. Top with more cheese then bake.', 4);
INSERT INTO Instruction VALUES (5,'Mix ingredients in a bowl. Use a hand mixer to make the merengue. Spread mix on the baking sheet in circles. Bake the macaron shells. Sandwich the merengue in between the shells', 10);
INSERT INTO Instruction VALUES (6,'Whisk the egg yolks. Stir in sugar water and also evaporated milk. Pour the custard filling into the shells until it is about 80% full. Bake for 15 to 20 minutes.Cool down for several minutes and then take the egg tarts out of the pan. Serve while still warm.', 16);
INSERT INTO Instruction VALUES (7,'Combine flour, baking soda and salt. Stir in eggs and mashed bananas until well blended. Stir banana mixture into flour mixture; stir just to moisten. Pour batter into prepared loaf pan.Bake in preheated oven until a toothpick inserted into center of the loaf comes out clean.', 30);

CREATE TABLE RecipeTime (
PrepTime TIME(0),
CookTime TIME(0),
TotalTime TIME(0) NOT NULL,
PRIMARY KEY (PrepTime, CookTime)
);

INSERT INTO RecipeTime VALUES ('00:10:00', '00:50:00', '01:00:00');
INSERT INTO RecipeTime VALUES ('00:20:00', '00:15:00', '00:35:00');
INSERT INTO RecipeTime VALUES ('00:03:00', '00:10:00', '00:13:00');
INSERT INTO RecipeTime VALUES ('03:00:00', '01:00:00', '04:00:00');
INSERT INTO RecipeTime VALUES ('04:00:00', '00:30:00', '04:30:00');
INSERT INTO RecipeTime VALUES ('01:00:00', '00:20:00', '01:20:00');
INSERT INTO RecipeTime VALUES ('01:00:00', '02:30:00', '03:30:00');

CREATE TABLE BookUser (
Username CHAR(20) PRIMARY KEY,
Password CHAR(20) NOT NULL,
Email CHAR(20) UNIQUE NOT NULL,
DietaryRestrictions VARCHAR(100),
Preferences VARCHAR(100)
);

INSERT INTO BookUser VALUES('User1', '123abc', 'user1@gmail.com', 'Vegan', 'Low-fat');
INSERT INTO BookUser VALUES('User2', '234bcd', 'user2@gmail.com', 'Meat-eater', 'Spicy');
INSERT INTO BookUser VALUES('User3', '345cde', 'user3@gmail.com', ' Lactose Intolerant', null);
INSERT INTO BookUser VALUES('User4', '456def', 'user4@gmail.com', 'Peanut allergy', 'Low-fat');
INSERT INTO BookUser VALUES('User5', '567efg', 'user5@gmail.com', 'Apple allergy', 'Low-fat');

CREATE TABLE Recipe (
ReID INTEGER AUTO_INCREMENT PRIMARY KEY,
SkillLevel CHAR(20),
Name VARCHAR(100) NOT NULL,
PrepTime TIME(0) NOT NULL,
CookTime TIME(0) NOT NULL,
InstructionID INTEGER UNIQUE NOT NULL,
Username CHAR(20) NOT NULL,
FOREIGN KEY (PrepTime, CookTime) REFERENCES RecipeTime (PrepTime, CookTime)
		ON DELETE NO ACTION
		ON UPDATE NO ACTION,
FOREIGN KEY (InstructionID) REFERENCES Instruction(InsID)
	ON DELETE CASCADE
	ON UPDATE NO ACTION,
FOREIGN KEY (Username) REFERENCES BookUser (Username)
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

INSERT INTO Recipe VALUES (1, 'Easy', 'Tiramisu', '00:10:00', '00:50:00', 1, 'User1');
INSERT INTO Recipe VALUES (2, 'Easy', 'Chocolate Cookie', '00:20:00', '00:15:00', 2, 'User1');
INSERT INTO Recipe VALUES (3, 'Medium', 'White Chocolate Brownie', '00:03:00', '00:10:00', 3, 'User2');
INSERT INTO Recipe VALUES (4, 'Hard', 'Lasagna', '03:00:00', '01:00:00', 4, 'User3');
INSERT INTO Recipe VALUES (5, 'Hard', 'Macaron', '04:00:00', '00:30:00', 5, 'User4');
INSERT INTO Recipe VALUES (6, 'Medium', 'Egg Tarts', '01:00:00', '00:20:00', 6, 'User1');
INSERT INTO Recipe VALUES (7, 'Easy', 'Banana Bread', '01:00:00', '02:30:00', 7, 'User5');


CREATE TABLE Ingredient (
Name VARCHAR(100) PRIMARY KEY,
Unit VARCHAR(50),
Type VARCHAR(50)
);

INSERT INTO Ingredient VALUES ('Flour', 'Cup', 'Baking');
INSERT INTO Ingredient VALUES ('Cocoa Powder', 'Cup', 'Baking');
INSERT INTO Ingredient VALUES ('Egg', null, 'Egg');
INSERT INTO Ingredient VALUES ('Ground Beef', 'Pound', 'Meat');
INSERT INTO Ingredient VALUES ('Tomato Paste', 'Ounce', 'Vegetable');

CREATE TABLE Equipment(
	Name VARCHAR(50) PRIMARY KEY
);

INSERT INTO Equipment VALUES ('Bowl');
INSERT INTO Equipment VALUES ('Baking Tray');
INSERT INTO Equipment VALUES ('Measuring Cup');
INSERT INTO Equipment VALUES ('Oven');
INSERT INTO Equipment VALUES ('Microwave');

CREATE TABLE RecipeContainsIngredient (
IName VARCHAR(100),
ReID INTEGER,
Amount CHAR(20) NOT NULL,
PRIMARY KEY (IName, ReID),
FOREIGN KEY (IName) REFERENCES Ingredient(Name)
	ON DELETE NO ACTION
	ON UPDATE CASCADE,
FOREIGN KEY (ReID) REFERENCES Recipe(ReID)
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

INSERT INTO RecipeContainsIngredient VALUES ('Flour', 1, '2');
INSERT INTO RecipeContainsIngredient VALUES ('Cocoa Powder', 1, '1/2');
INSERT INTO RecipeContainsIngredient VALUES ('Egg', 1, '4');
INSERT INTO RecipeContainsIngredient VALUES ('Ground Beef', 4, '1/2');
INSERT INTO RecipeContainsIngredient VALUES ('Tomato Paste', 4, '2');


CREATE TABLE RecipeUsesEquipment(
	EName VARCHAR(50),
	ReID  INTEGER,
	PRIMARY KEY (EName, ReID),
	FOREIGN KEY (EName) REFERENCES Equipment(Name)
		ON DELETE NO ACTION
		ON UPDATE CASCADE,
	FOREIGN KEY (ReID) REFERENCES Recipe(ReID)
		ON DELETE CASCADE
		ON UPDATE CASCADE);

INSERT INTO RecipeUsesEquipment VALUES ('Oven', 1);
INSERT INTO RecipeUsesEquipment VALUES ('Oven', 2);
INSERT INTO RecipeUsesEquipment VALUES ('Bowl', 1);
INSERT INTO RecipeUsesEquipment VALUES ('Bowl', 5);
INSERT INTO RecipeUsesEquipment VALUES ('Microwave', 3);

CREATE TABLE Tag (
	TName VARCHAR(40) PRIMARY KEY
);

INSERT INTO Tag VALUES('Vegetarian');
INSERT INTO Tag VALUES('Low-fat');
INSERT INTO Tag VALUES('Low-sodium');
INSERT INTO Tag VALUES('Vegan');
INSERT INTO Tag VALUES('Sweet');


CREATE TABLE RecipeLabelledTag (
TName VARCHAR(40),
ReID INTEGER,
PRIMARY KEY (TName, ReID),
FOREIGN KEY (TName) REFERENCES Tag(TName)
	ON DELETE CASCADE
	ON UPDATE CASCADE,
FOREIGN KEY (ReID) REFERENCES Recipe(ReID)
	ON DELETE CASCADE
	ON UPDATE CASCADE);

INSERT INTO RecipeLabelledTag VALUES ('Sweet', 1);
INSERT INTO RecipeLabelledTag VALUES ('Sweet', 2);
INSERT INTO RecipeLabelledTag VALUES ('Sweet', 3);
INSERT INTO RecipeLabelledTag VALUES ('Sweet', 5);
INSERT INTO RecipeLabelledTag VALUES ('Low-fat', 4);

CREATE TABLE AveRating (
	AverageScore REAL PRIMARY KEY,
    	Popularity CHAR(20)
);

INSERT INTO AveRating VALUES (1, 'Cold');
INSERT INTO AveRating VALUES (2, 'Warmer');
INSERT INTO AveRating VALUES (3, 'Warmer');
INSERT INTO AveRating VALUES (4, 'Hot');
INSERT INTO AveRating VALUES (5, 'Hottest');

CREATE TABLE RatingResult (
RID INTEGER AUTO_INCREMENT PRIMARY KEY,
AverageScore REAL,
NumOfUsers INTEGER,
FOREIGN KEY (AverageScore) REFERENCES AveRating(AverageScore)
	ON DELETE NO ACTION
	ON UPDATE CASCADE
);

INSERT INTO RatingResult VALUES (1, 3, 2);
INSERT INTO RatingResult VALUES (2, 3, 1);
INSERT INTO RatingResult VALUES (3, 1, 1);
INSERT INTO RatingResult VALUES (4, null, 0);
INSERT INTO RatingResult VALUES (5, 4, 1);


CREATE TABLE FinalRating (
    UserName CHAR(20),
    ReID INTEGER,
    RID INTEGER NOT NULL,
    Score INTEGER NOT NULL,
    PRIMARY KEY (UserName, ReID),
    FOREIGN KEY (UserName) REFERENCES BookUser(UserName)
    	ON DELETE CASCADE
	ON UPDATE CASCADE,
    FOREIGN KEY (RID) REFERENCES RatingResult(RID)
    	ON DELETE NO ACTION
	ON UPDATE CASCADE,
    FOREIGN KEY (ReID) REFERENCES Recipe(ReID)
	ON DELETE CASCADE
	ON UPDATE CASCADE
);

INSERT INTO FinalRating VALUES ('User1', 1, 1, 5);
INSERT INTO FinalRating VALUES ('User2', 2, 2, 3);
INSERT INTO FinalRating VALUES ('User3', 3, 3, 1);
INSERT INTO FinalRating VALUES ('User4', 5, 5, 4);
INSERT INTO FinalRating VALUES ('User5', 1, 1, 2);

CREATE TABLE Cuisine(
	Name CHAR(30) PRIMARY KEY
);

INSERT INTO Cuisine VALUES ('Thai');
INSERT INTO Cuisine VALUES ('Indian');
INSERT INTO Cuisine VALUES ('Western');
INSERT INTO Cuisine VALUES ('Italian');
INSERT INTO Cuisine VALUES ('French');

CREATE TABLE RecipesInCuisine(
	ReID INTEGER,
	CName CHAR(30),
	PRIMARY KEY (ReID, CName),
	FOREIGN KEY (ReID) REFERENCES Recipe(ReID)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (CName) REFERENCES Cuisine(Name)
		ON DELETE NO ACTION
		ON UPDATE CASCADE
);

INSERT INTO RecipesInCuisine VALUES (1, 'Western');
INSERT INTO RecipesInCuisine VALUES (2, 'Western');
INSERT INTO RecipesInCuisine VALUES (3, 'Western');
INSERT INTO RecipesInCuisine VALUES (4, 'Western');
INSERT INTO RecipesInCuisine VALUES (5, 'French');

CREATE TABLE ServingTimeOfDay(
	Name CHAR(20) PRIMARY KEY
);

INSERT INTO ServingTimeOfDay VALUES ('Breakfast');
INSERT INTO ServingTimeOfDay VALUES ('Lunch');
INSERT INTO ServingTimeOfDay VALUES ('Dinner');
INSERT INTO ServingTimeOfDay VALUES ('Tea-time');
INSERT INTO ServingTimeOfDay VALUES ('Brunch');

CREATE TABLE RecipeHasServingTime(
	ReID INTEGER,
	SName CHAR(20),
	PRIMARY KEY(ReID, SName),
	FOREIGN KEY(ReID) REFERENCES Recipe(ReID)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY(SName) REFERENCES ServingTimeOfDay(Name)
		ON DELETE NO ACTION
		ON UPDATE CASCADE
);

INSERT INTO RecipeHasServingTime VALUES(1, 'Breakfast');
INSERT INTO RecipeHasServingTime VALUES(2, 'Breakfast');
INSERT INTO RecipeHasServingTime VALUES(2, 'Brunch');
INSERT INTO RecipeHasServingTime VALUES(4, 'Dinner');
INSERT INTO RecipeHasServingTime VALUES(3, 'Tea-time');


CREATE TABLE BookKeeper (
	Username CHAR(20) PRIMARY KEY,
   	Password CHAR(20) NOT NULL
);

INSERT INTO BookKeeper VALUES('Keeper1', 'abcabc');
INSERT INTO BookKeeper VALUES('Keeper2', 'bcdbcd');
INSERT INTO BookKeeper VALUES('Keeper3', 'cdecde');
INSERT INTO BookKeeper VALUES('Keeper4', 'defdef');
INSERT INTO BookKeeper VALUES('Keeper5', 'efgefg');


CREATE TABLE ResetUserPassword(
	BKUsername CHAR(20),
	BUUsername CHAR(20),
	PRIMARY KEY(BKUsername, BUUsername),
	FOREIGN KEY (BKUsername) REFERENCES BookKeeper(Username)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (BUUsername) REFERENCES BookUser(Username)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

INSERT INTO ResetUserPassword VALUES('Keeper1', 'User5');
INSERT INTO ResetUserPassword VALUES('Keeper2', 'User1');
INSERT INTO ResetUserPassword VALUES('Keeper1', 'User2');
INSERT INTO ResetUserPassword VALUES('Keeper1', 'User1');
INSERT INTO ResetUserPassword VALUES('Keeper5', 'User3');

CREATE TABLE SendNotifTo(
	BKUsername CHAR(20),
	BUUsername CHAR(20),
	PRIMARY KEY(BKUsername, BUUsername),
	FOREIGN KEY (BKUsername) REFERENCES BookKeeper(Username)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY (BUUsername) REFERENCES BookUser(Username)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

INSERT INTO SendNotifTo VALUES('Keeper2', 'User1');
INSERT INTO SendNotifTo VALUES('Keeper4', 'User2');
INSERT INTO SendNotifTo VALUES('Keeper1', 'User1');
INSERT INTO SendNotifTo VALUES('Keeper1', 'User4');
INSERT INTO SendNotifTo VALUES('Keeper3', 'User2');

CREATE TABLE UserFavoritesRecipes(
	Username CHAR(20),
	ReID INTEGER,
	PRIMARY KEY(Username, ReID),
	FOREIGN KEY(Username) REFERENCES BookUser(Username)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY(ReID) REFERENCES Recipe(ReID)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

INSERT INTO UserFavoritesRecipes VALUES('User1', 1);
INSERT INTO UserFavoritesRecipes VALUES('User2', 1);
INSERT INTO UserFavoritesRecipes VALUES('User1', 4);
INSERT INTO UserFavoritesRecipes VALUES('User4', 1);
INSERT INTO UserFavoritesRecipes VALUES('User2', 2);
INSERT INTO UserFavoritesRecipes VALUES('User2', 3);
INSERT INTO UserFavoritesRecipes VALUES('User1', 3);
INSERT INTO UserFavoritesRecipes VALUES('User4', 3);
INSERT INTO UserFavoritesRecipes VALUES('User5', 3);
INSERT INTO UserFavoritesRecipes VALUES('User3', 3);

CREATE TABLE UserSearchesRecipes(
	Username CHAR(20),
	ReID INTEGER,
	Keyword VARCHAR(100) NOT NULL,
	PRIMARY KEY(Username, ReID),
	FOREIGN KEY(Username) REFERENCES BookUser(Username)
		ON DELETE CASCADE
		ON UPDATE CASCADE,
	FOREIGN KEY(ReID) REFERENCES Recipe(ReID)
		ON DELETE CASCADE
		ON UPDATE CASCADE
);

INSERT INTO UserSearchesRecipes VALUES('User5', 1, 'cake');
INSERT INTO UserSearchesRecipes VALUES('User5', 2, 'cookie');
INSERT INTO UserSearchesRecipes VALUES('User3', 3, 'brownie');
INSERT INTO UserSearchesRecipes VALUES('User4', 1, 'cake');
INSERT INTO UserSearchesRecipes VALUES('User1', 1, 'cake');

CREATE TABLE CreatedList(
	Username CHAR(20),
	Name VARCHAR(40),
	PRIMARY KEY(Username, Name),
	FOREIGN KEY(Username) REFERENCES BookUser(Username)
		ON DELETE CASCADE
		ON UPDATE CASCADE);

INSERT INTO CreatedList VALUES('User5', 'Favourite Breakfast Dishes');
INSERT INTO CreatedList  VALUES('User4', 'Easy to Make Desserts');
INSERT INTO CreatedList  VALUES('User3', 'Quick Lunch Ideas');
INSERT INTO CreatedList  VALUES('User2', 'Best Christmas Dishes');
INSERT INTO CreatedList  VALUES('User1', 'Favorite Desserts');
