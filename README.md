agenda_musical/
│
├── app/                   
│   ├── Http/              
│   │   ├── Controllers/   
│   │   │   ├── AuthController.php  
│   │   │   ├── CultoController.php  
│   │   │   ├── MusicaController.php  
│   │   │   ├── RepertorioController.php  
│   │   │   ├── EscalaController.php  
│   │   │   ├── InstrumentoController.php  
│   │   │   ├── UserController.php  
│   │   │   └── LogController.php  
│   │   ├── Middleware/    
│   │   │   ├── Authenticate.php  
│   │   │   ├── AdminMiddleware.php  
│   │   │   └── CorsMiddleware.php  
│   ├── Models/            
│   │   ├── User.php       
│   │   ├── Culto.php      
│   │   ├── Musica.php     
│   │   ├── Repertorio.php 
│   │   ├── Escala.php     
│   │   ├── Instrumento.php
│   │   ├── TokenRecuperacao.php
│   │   └── Log.php
│   ├── Services/          
│   │   ├── AuthService.php 
│   │   ├── CultoService.php 
│   │   ├── MusicaService.php 
│   │   ├── RepertorioService.php 
│   │   ├── EscalaService.php 
│   │   ├── EmailService.php 
│   │   └── NotificationService.php  
│
├── database/              
│   ├── factories/         
│   ├── migrations/        
│   ├── seeders/           
│   ├── factories/         
│
├── public/                
│   ├── assets/            
│   ├── index.php          
│
├── resources/             
│   ├── views/             
│   │   ├── auth/          
│   │   │   ├── login.blade.php      
│   │   │   ├── register.blade.php   
│   │   │   └── forgot-password.blade.php 
│   │   ├── dashboard/     
│   │   │   ├── cultos.blade.php      
│   │   │   ├── musicas.blade.php      
│   │   │   ├── repertorios.blade.php      
│   │   │   ├── escalas.blade.php      
│   │   │   ├── usuarios.blade.php      
│   │   │   └── logs.blade.php      
│   │   ├── layout/        
│   │   │   ├── header.blade.php     
│   │   │   ├── footer.blade.php     
│   │   │   └── sidebar.blade.php    
│   │   └── errors/        
│   │       ├── 404.blade.php        
│   │       └── 500.blade.php        
│
├── routes/                
│   ├── web.php        
│   ├── api.php        
│
├── tests/                 
│   └── ExampleTest.php    
│
├── .env                   
├── .gitignore             
├── composer.json          
├── artisan
├── package.json          
└── README.md              


CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo ENUM('admin', 'musico', 'usuario') DEFAULT 'usuario',
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE cultos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    data_hora DATETIME NOT NULL,
    local VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE musicas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(100) NOT NULL,
    cantor_banda VARCHAR(100),
    tom VARCHAR(5),
    bpm INT,
    link VARCHAR(255),
    arquivo VARCHAR(255),
    criado_em TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE instrumentos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(50) UNIQUE NOT NULL
);

CREATE TABLE repertorios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    culto_id INT NOT NULL,
    musica_id INT NOT NULL,
    categoria ENUM('criança', 'adolescente', 'jovem', 'senhoras') NOT NULL,
    FOREIGN KEY (culto_id) REFERENCES cultos(id) ON DELETE CASCADE,
    FOREIGN KEY (musica_id) REFERENCES musicas(id) ON DELETE CASCADE
);

CREATE TABLE escalas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    culto_id INT NOT NULL,
    usuario_id INT NOT NULL,
    instrumento_id INT NOT NULL,
    FOREIGN KEY (culto_id) REFERENCES cultos(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (instrumento_id) REFERENCES instrumentos(id) ON DELETE SET NULL
);

CREATE TABLE tokens_recuperacao (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    token VARCHAR(255) NOT NULL,
    expira_em DATETIME NOT NULL,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    acao VARCHAR(255) NOT NULL,
    data_hora TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
);
# agenda_musical
