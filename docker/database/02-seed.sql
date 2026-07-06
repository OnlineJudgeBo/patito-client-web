SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;

INSERT INTO `classification` (`classification_id`, `topic_id`, `name`) VALUES
(1, 1, 'Stacks'),
(2, 1, 'Queues'),
(3, 1, 'Deques'),
(4, 1, 'Arrays'),
(5, 1, '2D Array'),
(6, 1, 'Disjoint Sets'),
(7, 1, 'Fenwick Tree'),
(8, 1, 'Priority Queue'),
(9, 1, 'Segment Tree'),
(10, 1, 'Suffix Array'),
(11, 1, 'Matrix'),
(12, 1, 'Matrix Exponentiation'),
(13, 2, 'Binary Search'),
(14, 2, 'DFS'),
(15, 2, 'BFS'),
(16, 2, 'Dijkstra'),
(17, 2, 'Backtracking'),
(18, 2, 'Bitwise Operation'),
(19, 2, 'Brute Force'),
(20, 2, 'Divide and Conquer'),
(21, 2, 'Greedy'),
(22, 2, 'Sorting'),
(23, 2, 'Ternary Search'),
(24, 2, 'Two Pointers'),
(25, 3, 'DP'),
(26, 3, 'Bitmask-DP'),
(27, 3, 'DP Optimization'),
(28, 3, 'Knapsack'),
(29, 3, 'Meet-in-the-Middle'),
(30, 4, 'Combinatorics'),
(31, 4, 'Game Theory'),
(32, 4, 'Geometry'),
(33, 4, 'Math'),
(34, 4, 'Number Theory'),
(35, 4, 'Prime Factorization'),
(36, 4, 'Sieve of Eratosthenes'),
(37, 4, 'Chinese Remainder Theorem'),
(38, 4, 'Factorization'),
(39, 5, 'Graphs'),
(40, 5, 'Convex Hull'),
(41, 5, 'Disjoint Sets'),
(42, 5, 'Flows'),
(43, 5, 'Shortest Paths'),
(44, 5, 'Tarjan'),
(45, 5, 'Trees'),
(46, 6, 'Bitmasks'),
(47, 6, 'FFT'),
(48, 6, 'Hashing'),
(49, 6, 'Recursion'),
(50, 6, 'Segment Tree'),
(51, 6, 'String Suffix Structures'),
(52, 6, 'Sweep Line'),
(53, 6, 'XOR'),
(54, 7, '2-SAT'),
(55, 7, 'Ad-Hoc'),
(56, 7, 'Constructive Algorithms'),
(57, 7, 'Expression Parsing'),
(58, 7, 'Probabilities'),
(59, 7, 'Queries'),
(60, 7, 'Randomized Algorithms'),
(61, 7, 'Schedules'),
(62, 7, 'Scanline'),
(63, 7, 'Sqrt Decomposition'),
(64, 8, 'Beginner'),
(65, 8, 'Basic Arithmetic'),
(66, 8, 'Very Easy'),
(67, 8, 'Trivial'),
(68, 9, 'Adhoc'),
(69, 9, 'Bracket Sequences'),
(71, 8, 'Print'),
(72, 8, 'Sum'),
(73, 8, 'Implementation'),
(74, 14, 'Geometry'),
(75, 14, 'Greedy'),
(76, 14, 'Permutations'),
(77, 14, 'Combinations'),
(78, 14, 'Binomial Coefficients'),
(79, 14, 'Pigeonhole Principle'),
(80, 14, 'Inclusion-Exclusion Principle'),
(81, 14, 'Counting Problems'),
(82, 15, 'Graph Traversal'),
(83, 15, 'Shortest Path Algorithms'),
(84, 15, 'Minimum Spanning Tree'),
(85, 15, 'Network Flow'),
(86, 15, 'Matching'),
(87, 15, 'Graph Coloring'),
(88, 15, 'Planar Graphs'),
(89, 16, 'String Matching'),
(90, 16, 'Suffix Structures'),
(91, 16, 'Trie'),
(92, 16, 'Z-Algorithm'),
(93, 16, 'Aho-Corasick Algorithm'),
(94, 16, 'KMP Algorithm'),
(95, 16, 'Rabin-Karp Algorithm'),
(96, 17, 'Segment Tree'),
(97, 17, 'Fenwick Tree'),
(98, 17, 'Splay Tree'),
(99, 17, 'AVL Tree'),
(100, 17, 'Treap'),
(101, 17, 'Heavy-Light Decomposition'),
(102, 17, 'Persistent Data Structures'),
(103, 18, 'Number Theory'),
(104, 18, 'Modular Arithmetic'),
(105, 18, 'Combinatorial Game Theory'),
(106, 18, 'Probability'),
(107, 18, 'Linear Algebra'),
(108, 18, 'Fourier Transform'),
(109, 18, 'Fast Matrix Multiplication'),
(110, 5, 'Simulation'),
(111, 19, 'Number Theory'),
(112, 19, 'Strategy Games'),
(113, 5, 'Basic Arithmetic'),
(114, 5, 'Sorting and Searching'),
(115, 13, 'Knuth-Morris-Pratt (KMP)'),
(116, 2, 'Conditional Sorting'),
(117, 13, 'Simulation'),
(118, 13, 'Boyer-Moore'),
(119, 13, 'Aho-Corasick');

INSERT INTO sites (site_id, name) VALUES
    (1, 'Patito Demo');

INSERT INTO roles (role_id, role_name) VALUES
    (1, 'Administrador'),
    (2, 'Docente'),
    (3, 'Auxiliar');

INSERT INTO programing_language (language_id, name) VALUES
    (3, 'java'),
    (16, 'c'),
    (19, 'python');

-- Cuentas locales documentadas en Readme.md:
-- estudiante: patito / patito
-- administrador: patitoAdmin / patitoAdmin
-- El cliente admite hashes MD5 heredados para compatibilidad.
INSERT INTO users (user_id, site_id, ip, accesstime, password, reg_time, is_deleted, is_active) VALUES
    ('patito', 1, '127.0.0.1', NOW(), '3411f6d521ed0d17b6953e5741eaecca', NOW(), 0, 1),
    ('patitoAdmin', 1, '127.0.0.1', NOW(), '767f867231f257581e37debf28c6e2a3', NOW(), 0, 1);

INSERT INTO user_profiles (user_id, site_id, email, nick, school, lastname, pais_id) VALUES
    ('patito', 1, 'patito@example.test', 'Estudiante Patito', 'Institución de ejemplo', 'Demo', 0),
    ('patitoAdmin', 1, 'admin@example.test', 'Administrador Patito', 'Institución de ejemplo', 'Demo', 0);

INSERT INTO user_activity (user_id, site_id, submit, solved) VALUES
    ('patito', 1, 8, 5),
    ('patitoAdmin', 1, 4, 3);

INSERT INTO user_settings (user_id, site_id, volume, language, obi, institucion_id) VALUES
    ('patito', 1, 0, 19, 0, -1),
    ('patitoAdmin', 1, 0, 19, 0, -1);

INSERT INTO user_roles (user_id, site_id, role_id) VALUES
    ('patitoAdmin', 1, 1);

INSERT INTO privilege (privilege_id, user_id, rightstr, defunct) VALUES
    (1, 'patitoAdmin', 'administrator', 'N');

INSERT INTO problem
    (problem_id, title, description, input, output, sample_input, sample_output, hint, source,
     in_date, time_limit, memory_limit, defunct, accepted, submit, solved, tags2)
VALUES
    (1001, 'Suma de dos números', '<p>Lee dos enteros y muestra su suma.</p>', '<p>Dos enteros A y B.</p>', '<p>La suma A+B.</p>', '2 3', '5', '', 'Patito QA', NOW(), 1000, 128, 'N', 2, 3, 2, 'implementación'),
    (1002, 'Número par', '<p>Determina si un entero es par.</p>', '<p>Un entero N.</p>', '<p>SI o NO.</p>', '8', 'SI', '', 'Patito QA', NOW(), 1000, 128, 'N', 1, 2, 1, 'condicionales'),
    (1003, 'Mayor de tres', '<p>Encuentra el mayor de tres enteros.</p>', '<p>Tres enteros.</p>', '<p>El valor mayor.</p>', '4 9 2', '9', '', 'Patito QA', NOW(), 1000, 128, 'N', 1, 1, 1, 'condicionales'),
    (1004, 'Tabla de multiplicar', '<p>Muestra la tabla de N del 1 al 10.</p>', '<p>Un entero N.</p>', '<p>Diez productos, uno por línea.</p>', '2', '2\n4\n6\n8\n10\n12\n14\n16\n18\n20', '', 'Patito QA', NOW(), 1000, 128, 'N', 1, 2, 1, 'ciclos'),
    (1005, 'Factorial', '<p>Calcula N! para 0 ≤ N ≤ 12.</p>', '<p>Un entero N.</p>', '<p>El factorial de N.</p>', '5', '120', '', 'Patito QA', NOW(), 1000, 128, 'N', 1, 1, 1, 'ciclos'),
    (1006, 'Contar vocales', '<p>Cuenta las vocales de una línea.</p>', '<p>Una línea de texto.</p>', '<p>La cantidad de vocales.</p>', 'Patito', '3', '', 'Patito QA', NOW(), 1000, 128, 'N', 0, 1, 0, 'cadenas'),
    (1007, 'Invertir una cadena', '<p>Imprime una cadena en orden inverso.</p>', '<p>Una cadena sin espacios.</p>', '<p>La cadena invertida.</p>', 'codigo', 'ogidoc', '', 'Patito QA', NOW(), 1000, 128, 'N', 1, 1, 1, 'cadenas'),
    (1008, 'Máximo de un arreglo', '<p>Encuentra el máximo de N números.</p>', '<p>N seguido de N enteros.</p>', '<p>El máximo.</p>', '5\n1 7 3 4 2', '7', '', 'Patito QA', NOW(), 1000, 128, 'N', 0, 0, 0, 'arreglos'),
    (1009, '¿Es primo?', '<p>Determina si N es primo.</p>', '<p>Un entero N ≥ 2.</p>', '<p>SI o NO.</p>', '17', 'SI', '', 'Patito QA', NOW(), 1000, 128, 'N', 0, 0, 0, 'matemática'),
    (1010, 'Serie Fibonacci', '<p>Muestra el término N de Fibonacci, con F0=0 y F1=1.</p>', '<p>Un entero N.</p>', '<p>FN.</p>', '10', '55', '', 'Patito QA', NOW(), 1000, 128, 'N', 0, 1, 0, 'dinámica');

INSERT INTO problems_site (problem_id, site_id, is_active)
SELECT problem_id, 1, 1 FROM problem WHERE problem_id BETWEEN 1001 AND 1010;

INSERT INTO contest
    (contest_id, title, start_time, end_time, defunct, description, private, track, level)
VALUES
    (2001, 'Concurso de bienvenida', DATE_SUB(NOW(), INTERVAL 10 DAY), DATE_SUB(NOW(), INTERVAL 9 DAY),
     'N', 'Primer concurso de ejemplo con problemas introductorios.', 0, 'GENERAL', 'PRACTICE'),
    (2002, 'Práctica inicial abierta', DATE_SUB(NOW(), INTERVAL 1 DAY), DATE_ADD(NOW(), INTERVAL 30 DAY),
     'N', 'Concurso activo para explorar la plataforma.', 0, 'GENERAL', 'PRACTICE');

INSERT INTO contest_site (contest_id, site_id) VALUES
    (2001, 1),
    (2002, 1);

INSERT INTO contest_problem (contest_id, problem_id, title, num)
SELECT 2002, problem_id, title, problem_id - 1001
FROM problem WHERE problem_id BETWEEN 1001 AND 1010;

INSERT INTO contest_problem (contest_id, problem_id, title, num)
SELECT 2001, problem_id, title, problem_id - 1001
FROM problem WHERE problem_id BETWEEN 1001 AND 1005;

INSERT INTO contest_programming_language (contest_id, language_id) VALUES
    (2001, 3), (2001, 16), (2001, 19),
    (2002, 3), (2002, 16), (2002, 19);

INSERT INTO contest_user (contest_id, user_id, site_id, is_owner) VALUES
    (2001, 'patitoAdmin', 1, 1),
    (2002, 'patitoAdmin', 1, 1);

INSERT INTO solution
    (solution_id, problem_id, user_id, time, memory, in_date, result, language, ip,
     contest_id, is_virtual, num, code_length, judgetime, pass_rate, site_id)
VALUES
    (5001, 1001, 'patito', 3, 1024, DATE_SUB(NOW(), INTERVAL 8 DAY), 4, 19, '127.0.0.1', 2001, 0, 0, 46, NOW(), 0.99, 1),
    (5002, 1002, 'patito', 2, 1024, DATE_SUB(NOW(), INTERVAL 8 DAY), 6, 19, '127.0.0.1', 2001, 0, 1, 61, NOW(), 0.00, 1),
    (5003, 1002, 'patito', 2, 1024, DATE_SUB(NOW(), INTERVAL 8 DAY), 4, 19, '127.0.0.1', 2001, 0, 1, 64, NOW(), 0.99, 1),
    (5004, 1003, 'patito', 3, 1024, DATE_SUB(NOW(), INTERVAL 7 DAY), 4, 19, '127.0.0.1', 2001, 0, 2, 75, NOW(), 0.99, 1),
    (5005, 1004, 'patito', 4, 1024, DATE_SUB(NOW(), INTERVAL 6 DAY), 6, 19, '127.0.0.1', 2001, 0, 3, 70, NOW(), 0.40, 1),
    (5006, 1004, 'patito', 4, 1024, DATE_SUB(NOW(), INTERVAL 6 DAY), 4, 19, '127.0.0.1', 2001, 0, 3, 82, NOW(), 0.99, 1),
    (5007, 1005, 'patito', 3, 1024, DATE_SUB(NOW(), INTERVAL 5 DAY), 4, 19, '127.0.0.1', 2001, 0, 4, 88, NOW(), 0.99, 1),
    (5008, 1006, 'patito', 2, 1024, DATE_SUB(NOW(), INTERVAL 1 DAY), 6, 19, '127.0.0.1', 2002, 0, 5, 110, NOW(), 0.50, 1),
    (5009, 1001, 'patitoAdmin', 2, 1024, DATE_SUB(NOW(), INTERVAL 4 DAY), 4, 16, '127.0.0.1', 2001, 0, 0, 92, NOW(), 0.99, 1),
    (5010, 1003, 'patitoAdmin', 2, 1024, DATE_SUB(NOW(), INTERVAL 3 DAY), 4, 16, '127.0.0.1', 2001, 0, 2, 120, NOW(), 0.99, 1),
    (5011, 1007, 'patitoAdmin', 2, 1024, DATE_SUB(NOW(), INTERVAL 1 DAY), 4, 19, '127.0.0.1', 2002, 0, 6, 60, NOW(), 0.99, 1),
    (5012, 1010, 'patitoAdmin', 2, 1024, NOW(), 6, 19, '127.0.0.1', 2002, 0, 9, 95, NOW(), 0.20, 1);

INSERT INTO source_code (solution_id, source) VALUES
    (5001, 'a,b=map(int,input().split())\nprint(a+b)'),
    (5002, 'n=int(input())\nprint(\"SI\")'),
    (5003, 'n=int(input())\nprint(\"SI\" if n%2==0 else \"NO\")'),
    (5004, 'print(max(map(int,input().split())))'),
    (5005, 'n=int(input())\nfor i in range(10): print(n*i)'),
    (5006, 'n=int(input())\nfor i in range(1,11): print(n*i)'),
    (5007, 'n=int(input())\nr=1\nfor i in range(2,n+1): r*=i\nprint(r)'),
    (5008, 's=input().lower()\nprint(sum(c in \"aeiou\" for c in s))'),
    (5009, '#include <stdio.h>\nint main(){int a,b;scanf(\"%d%d\",&a,&b);printf(\"%d\\n\",a+b);}'),
    (5010, '#include <stdio.h>\nint main(){int a,b,c;scanf(\"%d%d%d\",&a,&b,&c);printf(\"%d\\n\",a>b?(a>c?a:c):(b>c?b:c));}'),
    (5011, 'print(input()[::-1])'),
    (5012, 'n=int(input())\nprint(n)');

COMMIT;
SET FOREIGN_KEY_CHECKS = 1;
