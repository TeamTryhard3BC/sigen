<?php
    include("PuenteMySQL.php");
    include("configuracion.php");
    $pwhash1 = password_hash("root", PASSWORD_BCRYPT);
    $pwhash2 = password_hash("entrenador", PASSWORD_BCRYPT);
    $pwhash3 = password_hash("cliente", PASSWORD_BCRYPT);

    //Consulta que creará las tablas de la base de datos del sistema
    $sql = "

    CREATE DATABASE IF NOT EXISTS teamtryhard_sigen;
    CREATE TABLE IF NOT EXISTS Persona (
    codigoPersona INT(6),
    nombre VARCHAR(24),
    apellido VARCHAR(24),
    fechaNacimiento DATE,
    nombreUsuario VARCHAR(40),
    fechaRegistro DATE DEFAULT CURRENT_DATE,
    -- nombreUsuario está compuesto por TipoDocumento y nroDocumento (separados por un guion)
    PRIMARY KEY (codigoPersona)
);
CREATE TABLE IF NOT EXISTS Usuario (
    tipoDocumento VARCHAR(16),
    nroDocumento INT(16),
    contrasena VARCHAR(255),
    rol ENUM('Cliente', 'Entrenador', 'Administrador'), 
    activo BOOLEAN DEFAULT TRUE,
    PRIMARY KEY (tipoDocumento, nroDocumento)
);

    -- Tabla: GrupoMuscular
    CREATE TABLE IF NOT EXISTS GrupoMuscular (
        nombreMusculo VARCHAR(18) PRIMARY KEY
    );

    -- Tabla: Entrenador
    CREATE TABLE IF NOT EXISTS Entrenador (
        codigoPersona INT(6),
        PRIMARY KEY (codigoPersona),
        FOREIGN KEY (codigoPersona) REFERENCES Persona(codigoPersona)
    );

    -- Tabla: Cliente
    CREATE TABLE IF NOT EXISTS Cliente (
        codigoPersona INT(6),
        PRIMARY KEY (codigoPersona),
        FOREIGN KEY (codigoPersona) REFERENCES Persona(codigoPersona)
    );

    -- Tabla: Deportista
    CREATE TABLE IF NOT EXISTS Deportista (
        codigoPersona INT(6),
        PRIMARY KEY (codigoPersona),
        FOREIGN KEY (codigoPersona) REFERENCES Cliente(codigoPersona)
    );

    -- Tabla: Paciente
    CREATE TABLE IF NOT EXISTS Paciente (
        codigoPersona INT(6),
        PRIMARY KEY (codigoPersona),
        FOREIGN KEY (codigoPersona) REFERENCES Cliente(codigoPersona)
    );

    -- Tabla: EstadoP
    CREATE TABLE IF NOT EXISTS EstadoP (
        ID INT(6) PRIMARY KEY AUTO_INCREMENT,
        Estado VARCHAR(16)
    );

    -- Tabla: EstadoD
    CREATE TABLE IF NOT EXISTS EstadoD (
        ID INT(6) PRIMARY KEY AUTO_INCREMENT,
        Estado VARCHAR(16)
    );

    -- Tabla: Pago
    CREATE TABLE IF NOT EXISTS Pago (
        codigoPago INT(16) PRIMARY KEY AUTO_INCREMENT,
        metodoPago VARCHAR(16),
        cuotas INT(2),
        fechaPago DATE DEFAULT CURRENT_DATE
    );

    -- Tabla: Ejercicio
    CREATE TABLE IF NOT EXISTS Ejercicio (
        codigoEjercicio INT(4) PRIMARY KEY,
        nombreEjercicio VARCHAR(24),
        descripcion VARCHAR(180),
        musculoTrabajado VARCHAR(18) 
    );

    -- Tabla: Combo
    CREATE TABLE IF NOT EXISTS Combo (
        codigoCombo INT(4) PRIMARY KEY,
        nombreCombo VARCHAR(18),
        descripcion VARCHAR(140)
    );

    -- Tabla: Deporte
    CREATE TABLE IF NOT EXISTS Deporte (
        codigoDeporte INT(4) PRIMARY KEY AUTO_INCREMENT,
        nombreDeporte VARCHAR(16),
        descripcion VARCHAR(80),
        reglas VARCHAR(240)
    );

    CREATE TABLE IF NOT EXISTS Calificacion (
        idCalificacion INT(6) PRIMARY KEY AUTO_INCREMENT,
        puntajeCliente INT,
        fechaCalificacion DATE DEFAULT CURRENT_DATE
    );
    
    -- Tabla de Relaciones: realizaCombo
    CREATE TABLE IF NOT EXISTS realizaCombo (
        ID INT (6),
        codigoPersona INT(6),
        PRIMARY KEY (ID),
        FOREIGN KEY (codigoPersona) REFERENCES Cliente(codigoPersona),
        FOREIGN KEY (ID) REFERENCES Combo(codigoCombo)
    );
    
    -- Tabla de Relaciones: Atiende
    CREATE TABLE IF NOT EXISTS Atiende (
        codigoCliente INT(6),
        codigoEntrenador INT(6),
        FOREIGN KEY (codigoCliente) REFERENCES Cliente(codigoPersona),
        FOREIGN KEY (codigoEntrenador) REFERENCES Entrenador(codigoPersona)
    );

    -- Tabla de Relaciones: Recibe
    CREATE TABLE IF NOT EXISTS Recibe (
        codigoPersona INT(6),
        idCalificacion INT(6),
        fechaCalificacion DATE DEFAULT CURRENT_DATE,
        PRIMARY KEY (codigoPersona, idCalificacion),
        FOREIGN KEY (codigoPersona) REFERENCES Cliente(codigoPersona),
        FOREIGN KEY (idCalificacion) REFERENCES Calificacion(idCalificacion)
    );


    -- Tabla de Relaciones: Realiza
    CREATE TABLE IF NOT EXISTS Realiza (
        codigoPago INT(16),
        codigoPersona INT(6),
        fechaPago DATE DEFAULT CURRENT_DATE,
        PRIMARY KEY (codigoPago),
        FOREIGN KEY (codigoPersona) REFERENCES Cliente(codigoPersona),
        FOREIGN KEY (codigoPago) REFERENCES Pago(codigoPago)
    );

    -- Tabla de Relaciones: Compone
    CREATE TABLE IF NOT EXISTS Compone (
        codigoDeporte INT(4),
        codigoCombo INT(4),
        PRIMARY KEY (codigoDeporte, codigoCombo),
        FOREIGN KEY (codigoDeporte) REFERENCES Deporte(codigoDeporte),
        FOREIGN KEY (codigoCombo) REFERENCES Combo(codigoCombo)
    );

    -- Tabla de Relaciones: Trabaja
    CREATE TABLE IF NOT EXISTS Trabaja (
        codigoEjercicio INT(4),
        nombreMusculo VARCHAR(18),
        PRIMARY KEY (codigoEjercicio, nombreMusculo),
        FOREIGN KEY (codigoEjercicio) REFERENCES Ejercicio(codigoEjercicio),
        FOREIGN KEY (nombreMusculo) REFERENCES GrupoMuscular(nombreMusculo)
    );

    -- Tabla de Relaciones: Crea
    CREATE TABLE IF NOT EXISTS Crea (
        codigoPersona INT(6),
        codigoCombo INT(4),
        PRIMARY KEY (codigoPersona, codigoCombo),
        FOREIGN KEY (codigoPersona) REFERENCES Entrenador(codigoPersona),
        FOREIGN KEY (codigoCombo) REFERENCES Combo(codigoCombo)
    );

    -- Tabla de Relaciones: Forma
    CREATE TABLE IF NOT EXISTS Forma (
        codigoEjercicio INT(4),
        codigoCombo INT(4),
        PRIMARY KEY (codigoEjercicio, codigoCombo),
        FOREIGN KEY (codigoEjercicio) REFERENCES Ejercicio(codigoEjercicio),
        FOREIGN KEY (codigoCombo) REFERENCES Combo(codigoCombo)
    );


    -- Tabla de Relaciones: Entrena
    CREATE TABLE IF NOT EXISTS Entrena (
        codigoPersona INT(6),
        nombreMusculo VARCHAR(18),
        PRIMARY KEY (codigoPersona, nombreMusculo),
        FOREIGN KEY (codigoPersona) REFERENCES Paciente(codigoPersona),
        FOREIGN KEY (nombreMusculo) REFERENCES grupoMuscular(nombreMusculo)
    );

    -- Tabla de Relaciones: PasaP
    CREATE TABLE IF NOT EXISTS PasaP (
        codigoPersona INT(6),
        ID INT (6),
        Estado VARCHAR(16),
        fechaEstado DATE DEFAULT CURRENT_DATE,
        PRIMARY KEY (codigoPersona, ID),
        FOREIGN KEY (codigoPersona) REFERENCES Paciente(codigoPersona),
        FOREIGN KEY (ID) REFERENCES EstadoP(ID)
    );

    -- Tabla de Relaciones: PasaD
    CREATE TABLE IF NOT EXISTS PasaD (
        codigoPersona INT(6),
        ID INT (6),
        Estado VARCHAR(16),
        fechaEstado DATE DEFAULT CURRENT_DATE,
        PRIMARY KEY (codigoPersona, ID),
        FOREIGN KEY (codigoPersona) REFERENCES Deportista(codigoPersona),
        FOREIGN KEY (ID) REFERENCES EstadoD(ID)
    );


    -- Tabla de Relaciones: Hace
    CREATE TABLE IF NOT EXISTS Hace (
        codigoPersona INT(6),
        codigoDeporte INT(4),
        PRIMARY KEY (codigoPersona, codigoDeporte),
        FOREIGN KEY (codigoPersona) REFERENCES Deportista(codigoPersona),
        FOREIGN KEY (codigoDeporte) REFERENCES Deporte(codigoDeporte)
    );

    CREATE TABLE IF NOT EXISTS agenda (
        idAgenda INT (32) PRIMARY KEY AUTO_INCREMENT,
        diaSemana ENUM('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'), 
        fechaReserva DATE, 
        cuposDisponibles INT
    );

    CREATE TABLE IF NOT EXISTS reserva (
        idAgenda INT (32),
        codigoPersona INT(6),
        FOREIGN KEY(idAgenda) REFERENCES agenda(idAgenda),
        FOREIGN KEY(codigoPersona) REFERENCES cliente(codigoPersona)
    );
        --
        --
        -- PRECARGA DE DATOS
        --
        --

        -- Usuarios
    INSERT INTO Usuario (tipoDocumento, nroDocumento, contrasena, rol) VALUES 
        ('ci', 54882751, '$pwhash1', 'Administrador'),
        ('ci', 54883102, '$pwhash2', 'Entrenador'),
        ('pasaporte', 324621, '$pwhash3', 'Cliente'),
        ('ci', 59912340, '$pwhash3', 'Cliente'),
        ('ci', 58912340, '$pwhash3', 'Cliente');

        -- Personas
        INSERT INTO Persona (codigoPersona, nombre, apellido, fechaNacimiento, nombreUsuario) VALUES 
            (1, 'Mathias', 'Diaz', '2002-09-07', 'ci-54882751'),
            (2, 'Alejo', 'Tabares', '2006-01-10', 'ci-54883102'),
            (3, 'Brayan', 'Rivero', '2001-11-02', 'pasaporte-324621'),
            (4, 'Cristian', 'Carpio', '2006-11-02', 'ci-59912340'),
            (5, 'Dylan', 'Arrua', '2007-11-02', 'ci-58912340');

        INSERT INTO Entrenador VALUES (2);
        INSERT INTO Cliente VALUES (3);
        INSERT INTO Cliente VALUES (4);
        INSERT INTO Cliente VALUES (5);

        INSERT INTO agenda (diaSemana, fechaReserva, cuposDisponibles) 
            VALUES 
            ('Lunes', '2024-11-11', 50),
            ('Martes', '2024-11-12', 50),
            ('Miércoles', '2024-11-13', 50),
            ('Jueves', '2024-11-14', 50),
            ('Viernes', '2024-11-15', 50),
            ('Sábado', '2024-11-16', 50),
            ('Domingo', '2024-11-17', 50);
    ";

    //Ejecutamos la consulta multiple para crear las tablas
    $resultado = consulta_multiple($rootUser, $rootPass, $sql);

    if ($resultado) {
        //Si es exitosa, redirigimos a la seleccion de idioma o la URL dada en configuracion.php
        header('Location: ' . $URL);
    }
?>