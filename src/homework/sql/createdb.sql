create table test
( TEST_ID    VARCHAR(255)    NOT NULL,
  Title      VARCHAR(255)    NOT NULL check(Title <> ''),
  PRIMARY KEY(TEST_ID)
);

create table question
( QUESTION_ID    INTEGER       NOT NULL,
  Question       VARCHAR(255)  NOT NULL check(Question <> ''),
  Answer         VARCHAR(255)  NOT NULL check(Answer <> ''),
  Exact          TINYINT       NOT NULL,
  TEST_ID        VARCHAR(255)  NOT NULL,
  PRIMARY KEY(QUESTION_ID, TEST_ID),
  FOREIGN KEY(TEST_ID) references test(TEST_ID) on update cascade on delete cascade
);

insert into test values ('english-vocabulary', 'English to Dutch vocabulary test');
insert into question values (1, 'To be', 'Zijn', 1, 'english-vocabulary');
insert into question values (2, 'To have', 'Hebben', 1, 'english-vocabulary');
insert into question values (3, 'Brother', 'Broer', 1, 'english-vocabulary');
insert into question values (4, 'Sister', 'Zus', 1, 'english-vocabulary');
insert into question values (5, 'Father', 'Vader', 1, 'english-vocabulary');
insert into question values (6, 'Mother', 'Moeder', 1, 'english-vocabulary');
insert into question values (7, 'Cat', 'Kat', 1, 'english-vocabulary');
insert into question values (8, 'Dog', 'Hond', 1, 'english-vocabulary');
insert into question values (9, 'Football', 'Voetbal', 1, 'english-vocabulary');
insert into question values (10, 'City', 'Stad', 1, 'english-vocabulary');
insert into question values (11, 'Country', 'Land', 1, 'english-vocabulary');
insert into question values (12, 'Left', 'Links', 1, 'english-vocabulary');
insert into question values (13, 'Right', 'Rechts', 1, 'english-vocabulary');
insert into question values (14, 'Up', 'Boven', 1, 'english-vocabulary');
insert into question values (15, 'Down', 'Beneden', 1, 'english-vocabulary');

insert into test values ('geography', 'Geography test');
insert into question values (1, 'What is the capital of the Netherlands?', 'Amsterdam', 1, 'geography');
insert into question values (2, 'What is the capital of the United States?', 'Washington D.C.', 1, 'geography');
insert into question values (3, 'What is the capital of the United Kingdom?', 'London', 1, 'geography');
insert into question values (4, 'What is the capital of the Russian Federation?', 'Moscow', 1, 'geography');
insert into question values (5, 'What is the capital of Japan?', 'Tokyo', 1, 'geography');
insert into question values (6, 'What is the capital of China?', 'Beijing', 1, 'geography');
insert into question values (7, 'What is the capital of Germany?', 'Berlin', 1, 'geography');
insert into question values (8, 'What is the capital of France?', 'Paris', 1, 'geography');
insert into question values (9, 'What is the capital of Spain?', 'Madrid', 1, 'geography');
insert into question values (10, 'What is the capital of Belgium?', 'Brussels', 1, 'geography');
insert into question values (11, 'What is the capital of Austria?', 'Vienna', 1, 'geography');
insert into question values (12, 'What is the capital of Switzerland?', 'Bern', 1, 'geography');
insert into question values (13, 'What is the capital of Turkey?', 'Ankara', 1, 'geography');
insert into question values (14, 'What is the capital of Greece?', 'Athens', 1, 'geography');
insert into question values (15, 'What is the capital of Poland?', 'Warsaw', 1, 'geography');
insert into question values (16, 'What is the capital of Canada?', 'Ottawa', 1, 'geography');
insert into question values (17, 'What is the capital of Brazil?', 'Brasilia', 1, 'geography');
insert into question values (18, 'What is the capital of Peru?', 'Lima', 1, 'geography');
insert into question values (19, 'What is the capital of Australia?', 'Canberra', 1, 'geography');
insert into question values (20, 'What is the capital of Egypt?', 'Cairo', 1, 'geography');
insert into question values (21, 'What are the capitals of South Africa?', 'Cape town, Pretoria and Bloemfontein', 0, 'geography');

insert into test values ('math', 'Math test');
insert into question values (1, 'Solve the following equation: x - 2 = x + 6 + 2x', 'x = -4', 0, 'math');
insert into question values (2, 'Solve the following equation: -2x<sup>2</sup> = -2x - 24', 'x = 4 or x = -3', 0, 'math');
insert into question values (3, 'Solve the following equation: 2<sup>x + 1</sup> = 64', 'x = 5', 0, 'math');
insert into question values (4, 'Solve the following equation: <sup>2</sup>log x = 8', 'x = 256', 0, 'math');
insert into question values (5, 'Compute the derivative of the following formula: f(x) = 4x<sup>2</sup> - 2x + 4', 'f\'(x) = 8x - 2', 0, 'math');
insert into question values (6, 'Compute the derivative of the following formula: f(x) = sin(2x<sup>2</sup> + 2)', 'f\'(x) = 4x cos(2x<sup>2</sup> + 2)', 0, 'math');
insert into question values (7, 'Compute the primitive of the following formula: f(x) = 2x + 1', 'F(x) = x<sup>2</sup> + x', 0, 'math');
insert into question values (8, 'Given a triangle ABC, with angle B = 90 degrees, and edges AB = 3 and BC = 4. Compute the length of edge AC', 'AC = 5', 0, 'math');
insert into question values (9, 'Given a triangle ABC, with angle A = 30 degrees, angle B = 90 degrees and edge AC = 4. Compute the length of edge BC', 'BC = 2', 0, 'math');
insert into question values (10, 'Given a triangle ABC, with angle A = 30 degrees, angle B = 90 degrees and edge AC = 4. Compute the length of edge AB', 'AB = 2 sqrt(3)', 0, 'math');
