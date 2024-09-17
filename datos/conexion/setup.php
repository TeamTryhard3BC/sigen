<?php
    include("PuenteMySQL.php");
    include("configuracion.php");

    //Consulta que creará las tablas de la base de datos del sistema
    $sql = "
        -- Tabla: Persona
        CREATE TABLE IF NOT EXISTS Persona (
            codigoPersona INT(6),
            nombre VARCHAR(24),
            apellido VARCHAR(24),
            fechaNacimiento DATE,
            nombreUsuario VARCHAR(40),
            -- nombreUsuario está compuesto por TipoDocumento y nroDocumento (separados por un guion) *DOCUMENTAR*
            -- ^^^^^ PREFIJO CON EL NOMBRE DEL GRUPO (ej: teamtryhard_USUARIO)
            PRIMARY KEY (codigoPersona)
        );

        -- Tabla: Usuario
        CREATE TABLE IF NOT EXISTS Usuario (
            tipoDocumento VARCHAR(16),
            nroDocumento INT(16),
            contrasena VARCHAR(80),
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
            ID INT(6) PRIMARY KEY,
            Estado VARCHAR(16)
        );

        -- Tabla: EstadoD
        CREATE TABLE IF NOT EXISTS EstadoD (
            ID INT(6) PRIMARY KEY,
            Estado VARCHAR(16)
        );

        -- Tabla: Pago
        CREATE TABLE IF NOT EXISTS Pago (
            codigoPago INT(16) PRIMARY KEY,
            fechaPago DATE,
            metodoPago VARCHAR(16),
            cuotas INT(2)
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
            codigoDeporte INT(4) PRIMARY KEY,
            nombreDeporte VARCHAR(16),
            descripcion VARCHAR(80),
            reglas VARCHAR(240)
        );

        -- Tabla: Calificacion
        CREATE TABLE IF NOT EXISTS Calificacion (
            idCalificacion INT(6) PRIMARY KEY,
            nivel VARCHAR(30)
        );

        -- Tabla de Relaciones: Forma
        CREATE TABLE IF NOT EXISTS Forma (
            nombreMusculo VARCHAR(18),
            codigoDeporte INT(4),
            PRIMARY KEY (nombreMusculo, codigoDeporte),
            FOREIGN KEY (nombreMusculo) REFERENCES GrupoMuscular(nombreMusculo),
            FOREIGN KEY (codigoDeporte) REFERENCES Deporte(codigoDeporte)
        );

        -- Tabla de Relaciones: Atiende
        CREATE TABLE IF NOT EXISTS Atiende (
            codigoPersona INT(6),
            PRIMARY KEY (codigoPersona),
            FOREIGN KEY (codigoPersona) REFERENCES Persona(codigoPersona)
        );

        -- Tabla de Relaciones: Recibe
        CREATE TABLE IF NOT EXISTS Recibe (
            codigoPersona INT(6),
            idCalificacion INT(6),
            PRIMARY KEY (codigoPersona, idCalificacion),
            FOREIGN KEY (codigoPersona) REFERENCES Cliente(codigoPersona),
            FOREIGN KEY (idCalificacion) REFERENCES Calificacion(idCalificacion)
        );

        -- Tabla de Relaciones: Realiza
        CREATE TABLE IF NOT EXISTS Realiza (
            codigoPersona INT(6),
            codigoPago INT(16),
            PRIMARY KEY (codigoPersona, codigoPago),
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

        -- Tabla de Relaciones: Entrena
        CREATE TABLE IF NOT EXISTS Entrena (
            codigoPersona INT(6),
            codigoDeporte INT(4),
            nombreMusculo VARCHAR(18),
            PRIMARY KEY (codigoPersona, codigoDeporte, nombreMusculo),
            FOREIGN KEY (codigoPersona) REFERENCES Paciente(codigoPersona),
            FOREIGN KEY (codigoDeporte, nombreMusculo) REFERENCES Forma(codigoDeporte, nombreMusculo)
        );

        -- Tabla de Relaciones: PasaP
        CREATE TABLE IF NOT EXISTS PasaP (
            codigoPersona INT(6),
            ID INT(6),
            PRIMARY KEY (codigoPersona, ID),
            FOREIGN KEY (codigoPersona) REFERENCES Paciente(codigoPersona),
            FOREIGN KEY (ID) REFERENCES EstadoP(ID)
        );

        -- Tabla de Relaciones: PasaD
        CREATE TABLE IF NOT EXISTS PasaD (
            codigoPersona INT(6),
            ID INT(6),
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

        --
        --
        -- PRECARGA DE DATOS
        --
        --

        -- Usuarios
        INSERT INTO Usuario (tipoDocumento, nroDocumento, contrasena) VALUES 
            ('ci', 54443332, '123456'),
            ('ci', 53334441, '123457');

        -- Personas
        INSERT INTO Persona (codigoPersona, nombre, apellido, fechaNacimiento, nombreUsuario) VALUES 
            (123456, 'Mathias', 'Diaz', '2002-09-07', 'ci-54443332'),
            (123457, 'Alejo', 'Tabares', '2006-01-10', 'ci-53334441');

        -- Entrenadores
        INSERT INTO Entrenador (codigoPersona) VALUES 
            (123456);

        -- Clientes
        INSERT INTO Cliente (codigoPersona) VALUES 
            (123457);

        -- Ejercicios
        INSERT INTO Ejercicio (codigoEjercicio, nombreEjercicio, descripcion, musculoTrabajado) VALUES 
            (2244, 'Press Plano', 'Es un ejercicio que trabaja el pecho', 'Pecho'),
            (1122, 'Biceps con Mancuerna', 'Es un ejercicio que trabajan los biceps', 'Biceps'),
            (4499, 'Triceps con mancuerna', 'Es un ejercicio que trabajan los triceps', 'Triceps'),
            (4410, 'Sentadilla', 'Es un ejercicio que trabajan las piernas', 'Piernas');

        -- Combos
        INSERT INTO Combo (codigoCombo, nombreCombo, descripcion) VALUES 
            (6633, 'ComboAlejo', 'Este combo es para Alejo'),
            (2211, 'ComboMathias', 'Este combo es para Mathias'),
            (2222, 'ComboGenerico', 'Este combo es para Todos');

    ";

    //Ejecutamos la consulta multiple para crear las tablas
    $resultado = consulta_multiple($rootUser, $rootPass, $sql);

    if ($resultado) {
        //Si es exitosa, redirigimos a la seleccion de idioma o la URL dada en configuracion.php
        header('Location: ' . $URL);
    }
?>