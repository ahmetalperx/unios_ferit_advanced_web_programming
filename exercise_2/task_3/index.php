<?php

// ---------------------------------------------------------------------------------------------- //

// cd C:\Users\ahmetalper\Desktop\advanced_web_programming\exercise_2\task_3 && php -S localhost:8000

// http://localhost:8000/index.php

// ---------------------------------------------------------------------------------------------- //

define('ENCRYPTION_KEY', 'advanced_web_programming_encryption_key');

define('CIPHER_METHOD', 'AES-256-CBC');

define('UPLOAD_DIR', __DIR__ . '/uploads/');

// ---------------------------------------------------------------------------------------------- //

if (is_dir(UPLOAD_DIR) === false) {
    
    mkdir(UPLOAD_DIR, 0777, true);
    
}

// ---------------------------------------------------------------------------------------------- //

$upload_message = '';

$upload_status = '';

// ---------------------------------------------------------------------------------------------- //

if (($_SERVER['REQUEST_METHOD'] ?? '') === 'POST' && isset($_FILES['secure_file']) === true) {
    
    $file = $_FILES['secure_file'];
    
    $allowed_extensions = ['pdf', 'jpeg', 'jpg', 'png'];
    
    $file_extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if ($file['error'] !== UPLOAD_ERR_OK) {
        
        $upload_message = 'file upload error occurred';
        
        $upload_status = 'error';
        
    } elseif (in_array($file_extension, $allowed_extensions) === false) {
        
        $upload_message = 'invalid format, only pdf, jpeg, and png are allowed';
        
        $upload_status = 'error';
        
    } else {
        
        $file_content = file_get_contents($file['tmp_name']);
        
        $iv_length = openssl_cipher_iv_length(CIPHER_METHOD);
        
        $iv = openssl_random_pseudo_bytes($iv_length);
        
        $encrypted_content = openssl_encrypt($file_content, CIPHER_METHOD, ENCRYPTION_KEY, 0, $iv);
        
        $final_content = base64_encode($iv) . '::' . $encrypted_content;
        
        $target_filename = basename($file['name']) . '.enc';
        
        $target_filepath = UPLOAD_DIR . $target_filename;
        
        if (file_put_contents($target_filepath, $final_content) !== false) {
            
            $upload_message = 'file \'' . htmlspecialchars($file['name']) . '\' encrypted and saved';
            
            $upload_status = 'success';
            
        } else {
            
            $upload_message = 'failed to save the encrypted file';
            
            $upload_status = 'error';
            
        }
        
    }
    
}

// ---------------------------------------------------------------------------------------------- //

if (isset($_GET['download']) === true) {
    
    $filename = basename($_GET['download']);
    
    $filepath = UPLOAD_DIR . $filename;
    
    if (file_exists($filepath) === true) {
        
        $encrypted_data = file_get_contents($filepath);
        
        $parts = explode('::', $encrypted_data, 2);
        
        if (count($parts) === 2) {
            
            $iv = base64_decode($parts[0]);
            
            $encrypted_content = $parts[1];
            
            $decrypted_content = openssl_decrypt($encrypted_content, CIPHER_METHOD, ENCRYPTION_KEY, 0, $iv);
            
            if ($decrypted_content !== false) {
                
                $original_filename = str_replace('.enc', '', $filename);
                
                header('Content-Description: File Transfer');
                
                $ext = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
                
                if ($ext === 'pdf') {
                    
                    header('Content-Type: application/pdf');
                    
                } elseif ($ext === 'png') {
                    
                    header('Content-Type: image/png');
                    
                } elseif ($ext === 'jpeg' || $ext === 'jpg') {
                    
                    header('Content-Type: image/jpeg');
                    
                } else {
                    
                    header('Content-Type: application/octet-stream');
                    
                }
                
                header('Content-Disposition: attachment; filename="' . $original_filename . '"');
                
                header('Expires: 0');
                
                header('Cache-Control: must-revalidate');
                
                header('Pragma: public');
                
                header('Content-Length: ' . strlen($decrypted_content));
                
                echo $decrypted_content;
                
                exit(0);
                
            } else {

                echo PHP_EOL . '[ ERROR ] | decryption failed, invalid key or corrupted file' . PHP_EOL . PHP_EOL;

                exit(1);

            }

        } else {

            echo PHP_EOL . '[ ERROR ] | file format is invalid or corrupted' . PHP_EOL . PHP_EOL;

            exit(1);

        }

    } else {

        echo PHP_EOL . '[ ERROR ] | requested file does not exist on the server' . PHP_EOL . PHP_EOL;

        exit(1);
        
    }
    
}

// ---------------------------------------------------------------------------------------------- //

$encrypted_files = [];

if (is_dir(UPLOAD_DIR) === true) {
    
    $files = scandir(UPLOAD_DIR);
    
    foreach ($files as $file) {
        
        if ($file !== '.' && $file !== '..' && strtolower(pathinfo($file, PATHINFO_EXTENSION)) === 'enc') {
            
            $encrypted_files[] = $file;
            
        }
        
    }
    
}

// ---------------------------------------------------------------------------------------------- //

?>

<!------------------------------------------------------------------------------------------------->

<!DOCTYPE html>

<!------------------------------------------------------------------------------------------------->

<html lang="en" class="dark">

<!------------------------------------------------------------------------------------------------->

<head>

    <!------------------------------------------------------------------------------------------------->

    <meta charset="UTF-8">

    <!------------------------------------------------------------------------------------------------->

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!------------------------------------------------------------------------------------------------->

    <title>Advanced Web Programming - Exercise 2 - Task 3</title>

    <!------------------------------------------------------------------------------------------------->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!------------------------------------------------------------------------------------------------->

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!------------------------------------------------------------------------------------------------->
    
    <script src="https://cdn.tailwindcss.com"></script>

    <!------------------------------------------------------------------------------------------------->

</head>

<!------------------------------------------------------------------------------------------------->

<body class="min-h-screen p-6 md:p-12 antialiased text-slate-300 bg-slate-900 bg-[radial-gradient(#334155_1px,transparent_1px)] bg-[size:20px_20px]" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    <!------------------------------------------------------------------------------------------------->

    <div class="max-w-7xl mx-auto">

        <!------------------------------------------------------------------------------------------------->

        <header class="text-center mb-16 mt-6">

            <!------------------------------------------------------------------------------------------------->

            <div class="inline-flex items-center justify-center p-3 bg-slate-800 rounded-2xl shadow-sm border border-slate-700/50 mb-6 relative overflow-hidden">
                
                <i class="fa-solid fa-shield-halved text-teal-500 text-2xl px-2 relative z-10"></i>
                
                <div class="absolute inset-0 bg-teal-500/10 blur-xl"></div>
                
            </div>

            <!------------------------------------------------------------------------------------------------->

            <h1 class="text-5xl md:text-6xl font-extrabold text-white tracking-tight mb-6">
                
                Secure <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-emerald-300">Vault</span>
                
            </h1>

            <!------------------------------------------------------------------------------------------------->

            <p class="text-slate-400 text-lg font-medium max-w-2xl mx-auto leading-relaxed">
                
                Task 3: Upload PDF, JPEG, or PNG files. They are encrypted using <strong class="text-teal-400">OpenSSL (AES-256-CBC)</strong> before hitting the disk. Download them to securely decrypt.
                
            </p>

            <!------------------------------------------------------------------------------------------------->

        </header>

        <!------------------------------------------------------------------------------------------------->

        <?php if ($upload_message !== ''): ?>
            
            <!------------------------------------------------------------------------------------------------->

            <div class="max-w-2xl mx-auto mb-10 p-4 rounded-2xl border <?php echo $upload_status === 'success' ? 'bg-teal-500/10 border-teal-500/30 text-teal-400' : 'bg-rose-500/10 border-rose-500/30 text-rose-400'; ?> flex items-center justify-center gap-3 font-semibold shadow-lg backdrop-blur-md transition-all">
                
                <i class="fa-solid <?php echo $upload_status === 'success' ? 'fa-circle-check' : 'fa-circle-exclamation'; ?> text-xl"></i>
                
                <?php echo htmlspecialchars($upload_message); ?>
                
            </div>

            <!------------------------------------------------------------------------------------------------->
            
        <?php endif; ?>

        <!------------------------------------------------------------------------------------------------->

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <!------------------------------------------------------------------------------------------------->

            <div class="lg:col-span-5">

                <!------------------------------------------------------------------------------------------------->

                <div class="bg-slate-800/80 backdrop-blur-md rounded-[2rem] shadow-xl border border-slate-700/50 p-8 h-full">
                    
                    <!------------------------------------------------------------------------------------------------->

                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                        
                        <i class="fa-solid fa-cloud-arrow-up text-teal-500"></i> Encrypt & Upload
                        
                    </h2>

                    <!------------------------------------------------------------------------------------------------->

                    <form action="index.php" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
                        
                        <!------------------------------------------------------------------------------------------------->

                        <div class="relative group cursor-pointer">
                            
                            <input type="file" name="secure_file" id="secure_file" required accept=".pdf,.jpg,.jpeg,.png" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                            
                            <div class="w-full border-2 border-dashed border-slate-600 rounded-2xl p-10 flex flex-col items-center justify-center gap-4 group-hover:border-teal-500/50 group-hover:bg-teal-500/5 transition-all duration-300 relative z-10">
                                
                                <div class="w-16 h-16 rounded-full bg-slate-700 flex items-center justify-center text-slate-400 group-hover:text-teal-400 group-hover:bg-slate-800 transition-colors shadow-inner">
                                    
                                    <i class="fa-solid fa-file-arrow-up text-2xl"></i>
                                    
                                </div>
                                
                                <div class="text-center">
                                    
                                    <p class="text-white font-semibold text-lg mb-1">Select a file to encrypt</p>
                                    
                                    <p class="text-slate-400 text-sm">Supported: PDF, JPEG, PNG</p>
                                    
                                </div>
                                
                            </div>
                            
                        </div>

                        <!------------------------------------------------------------------------------------------------->

                        <button type="submit" class="w-full bg-gradient-to-r from-teal-600 to-emerald-500 hover:from-teal-500 hover:to-emerald-400 text-white font-bold py-4 rounded-2xl shadow-lg shadow-teal-500/20 hover:shadow-teal-500/40 transition-all duration-300 flex items-center justify-center gap-2">
                            
                            <i class="fa-solid fa-lock"></i> Encrypt File
                            
                        </button>

                        <!------------------------------------------------------------------------------------------------->

                    </form>

                    <!------------------------------------------------------------------------------------------------->

                </div>

                <!------------------------------------------------------------------------------------------------->

            </div>

            <!------------------------------------------------------------------------------------------------->

            <div class="lg:col-span-7">

                <!------------------------------------------------------------------------------------------------->

                <div class="bg-slate-800/80 backdrop-blur-md rounded-[2rem] shadow-xl border border-slate-700/50 p-8 h-full">
                    
                    <!------------------------------------------------------------------------------------------------->

                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-3">
                        
                        <i class="fa-solid fa-server text-teal-500"></i> Server Vault
                        
                    </h2>

                    <!------------------------------------------------------------------------------------------------->

                    <?php if (empty($encrypted_files) === false): ?>

                        <!------------------------------------------------------------------------------------------------->

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                            <!------------------------------------------------------------------------------------------------->

                            <?php foreach ($encrypted_files as $enc_file): ?>

                                <!------------------------------------------------------------------------------------------------->

                                <div class="bg-slate-900/50 border border-slate-700/50 rounded-2xl p-5 flex flex-col hover:border-teal-500/30 transition-colors group">
                                    
                                    <!------------------------------------------------------------------------------------------------->

                                    <div class="flex items-start gap-4 mb-4">
                                        
                                        <div class="w-12 h-12 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 shrink-0 group-hover:text-teal-400 transition-colors">
                                            
                                            <i class="fa-solid fa-file-shield text-xl"></i>
                                            
                                        </div>
                                        
                                        <div class="overflow-hidden">
                                            
                                            <p class="text-white font-semibold text-sm truncate" title="<?php echo htmlspecialchars($enc_file); ?>">
                                                
                                                <?php echo htmlspecialchars($enc_file); ?>
                                                
                                            </p>
                                            
                                            <p class="text-xs text-slate-500 mt-1 uppercase tracking-wider font-bold">Encrypted</p>
                                            
                                        </div>
                                        
                                    </div>

                                    <!------------------------------------------------------------------------------------------------->

                                    <a href="?download=<?php echo urlencode($enc_file); ?>" class="mt-auto w-full bg-slate-800 hover:bg-slate-700 text-teal-400 font-semibold py-2.5 rounded-xl text-sm flex items-center justify-center gap-2 border border-slate-700 hover:border-slate-600 transition-all">
                                        
                                        <i class="fa-solid fa-unlock-keyhole"></i> Decrypt & Download
                                        
                                    </a>

                                    <!------------------------------------------------------------------------------------------------->

                                </div>

                                <!------------------------------------------------------------------------------------------------->

                            <?php endforeach; ?>

                            <!------------------------------------------------------------------------------------------------->

                        </div>

                        <!------------------------------------------------------------------------------------------------->

                    <?php else: ?>

                        <!------------------------------------------------------------------------------------------------->

                        <div class="h-64 flex flex-col items-center justify-center text-slate-500 border-2 border-dashed border-slate-700 rounded-2xl">
                            
                            <i class="fa-solid fa-box-open text-4xl mb-4 opacity-50"></i>
                            
                            <p class="font-medium">The vault is currently empty.</p>
                            
                        </div>

                        <!------------------------------------------------------------------------------------------------->

                    <?php endif; ?>

                    <!------------------------------------------------------------------------------------------------->

                </div>

                <!------------------------------------------------------------------------------------------------->

            </div>

            <!------------------------------------------------------------------------------------------------->

        </div>

        <!------------------------------------------------------------------------------------------------->

    </div>

    <!------------------------------------------------------------------------------------------------->
    
</body>

<!------------------------------------------------------------------------------------------------->

</html>

<!------------------------------------------------------------------------------------------------->
