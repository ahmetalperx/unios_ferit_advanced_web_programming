<?php

// ---------------------------------------------------------------------------------------------- //

// cd C:\Users\ahmetalper\Desktop\advanced_web_programming\exercise_2\task_2 && php -S localhost:8000

// http://localhost:8000/index.php

// ---------------------------------------------------------------------------------------------- //

$xml_file = __DIR__ . '/LV2.xml';

// ---------------------------------------------------------------------------------------------- //

if (file_exists($xml_file) === true) {

    $xml_data = simplexml_load_file($xml_file);

} else {

    $xml_data = false;

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

    <title>Advanced Web Programming - Exercise 2 - Task 2</title>

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

        <header class="text-center mb-20 mt-10">

            <div class="inline-flex items-center justify-center p-3 bg-slate-800 rounded-2xl shadow-sm border border-slate-700/50 mb-6">
                
                <i class="fa-solid fa-bolt text-teal-500 text-2xl px-2"></i>
            
            </div>

            <h1 class="text-5xl md:text-6xl font-extrabold text-white tracking-tight mb-6">

                Executive <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-500 to-emerald-300">Directory</span>

            </h1>

            <p class="text-slate-400 text-lg font-medium max-w-2xl mx-auto leading-relaxed">

                A premium showcase of our talented team members, dynamically parsed from the XML dataset with ultra-modern dark aesthetics.

            </p>

        </header>

        <!------------------------------------------------------------------------------------------------->

        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-10 items-stretch">

            <!------------------------------------------------------------------------------------------------->

            <?php if ($xml_data !== false && isset($xml_data -> record) === true): ?>

                <!------------------------------------------------------------------------------------------------->

                <?php foreach ($xml_data -> record as $person): ?>

                    <?php
                    
                        $gender = strtolower((string) $person -> spol);

                        $is_male = ($gender === 'male' || $gender === 'm');
                        
                        $badge_bg = $is_male ? 'bg-blue-500/20 text-blue-300 border-blue-500/30' : 'bg-rose-500/20 text-rose-300 border-rose-500/30';
                        
                        $icon = $is_male ? 'fa-mars' : 'fa-venus';

                        $initials = strtoupper(substr((string) $person -> ime, 0, 1) . substr((string) $person -> prezime, 0, 1));

                        $fallback_svg = 'data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 100 100\'><circle cx=\'50\' cy=\'50\' r=\'50\' fill=\'' . ($is_male ? '%230284c7' : '%23e11d48') . '\'/><text x=\'50\' y=\'55\' font-family=\'sans-serif\' font-size=\'38\' font-weight=\'bold\' fill=\'white\' text-anchor=\'middle\' dominant-baseline=\'middle\'>' . $initials . '</text></svg>';
                        
                    ?>

                    <!------------------------------------------------------------------------------------------------->

                    <div class="flex flex-col h-full bg-slate-800/80 backdrop-blur-md rounded-[2rem] shadow-xl border border-slate-700/50 overflow-hidden relative group transition-all duration-500 hover:-translate-y-2 hover:shadow-[0_20px_40px_rgb(0,0,0,0.3)] hover:border-teal-500/30">
                        
                        <div class="h-32 shrink-0 w-full relative border-b border-slate-700/50 bg-[#020617] bg-[linear-gradient(135deg,#0f172a_25%,transparent_25%),linear-gradient(225deg,#0f172a_25%,transparent_25%),linear-gradient(45deg,#0f172a_25%,transparent_25%),linear-gradient(315deg,#0f172a_25%,#020617_25%)] bg-[position:10px_0,10px_0,0_0,0_0] bg-[size:20px_20px]">
                            
                            <div class="absolute top-5 right-5 <?php echo $badge_bg; ?> border backdrop-blur-md px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                                
                                <i class="fa-solid <?php echo $icon; ?>"></i>
                                
                                <?php echo htmlspecialchars($person -> spol); ?>

                            </div>

                        </div>

                        <div class="px-8 pb-8 flex flex-col flex-1 relative">
                            
                            <div class="w-24 h-24 shrink-0 mx-auto -mt-12 bg-slate-800 rounded-full p-1.5 shadow-2xl relative z-10">
                                
                                <img src="<?php echo htmlspecialchars($person -> slika); ?>" alt="<?php echo htmlspecialchars($person -> ime . ' ' . $person -> prezime); ?>" loading="lazy" referrerpolicy="no-referrer" onerror="this.onerror=null; this.src='<?php echo $fallback_svg; ?>';" class="w-full h-full rounded-full object-cover bg-slate-900 border border-slate-700">
                            
                            </div>

                            <div class="h-16 flex items-center justify-center mt-4 mb-2">
                                
                                <h2 class="text-2xl font-bold text-white text-center tracking-tight group-hover:text-teal-400 transition-colors line-clamp-2">

                                    <?php echo htmlspecialchars($person -> ime . ' ' . $person -> prezime); ?>

                                </h2>
                            
                            </div>

                            <div class="h-6 flex items-center justify-center mb-8">
                                
                                <a href="mailto:<?php echo htmlspecialchars($person -> email); ?>" class="flex items-center justify-center gap-2 text-slate-400 hover:text-teal-400 font-medium text-sm transition-colors truncate">
                                    
                                    <i class="fa-regular fa-envelope"></i>

                                    <?php echo htmlspecialchars($person -> email); ?>

                                </a>
                            
                            </div>

                            <div class="mt-auto bg-slate-900/50 rounded-2xl p-6 border border-slate-700/50 transition-colors group-hover:bg-slate-800/80 group-hover:border-teal-500/20">
                                
                                <div class="flex items-center gap-2 mb-3">
                                    
                                    <div class="w-8 h-8 shrink-0 rounded-full bg-slate-800 shadow-inner flex items-center justify-center text-slate-500 group-hover:text-teal-400 transition-colors border border-slate-700/50">
                                        
                                        <i class="fa-solid fa-quote-left text-xs"></i>
                                    
                                    </div>
                                    
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest group-hover:text-teal-400 transition-colors">Biography</span>
                                </div>
                                
                                <p class="text-slate-300 text-sm leading-relaxed font-medium line-clamp-3 hover:line-clamp-none min-h-[4.5rem] transition-all duration-300 cursor-pointer" title="Hover to expand">
                                    
                                    <?php echo htmlspecialchars($person -> zivotopis); ?>
                                
                                </p>
                            
                            </div>

                        </div>

                    </div>

                    <!------------------------------------------------------------------------------------------------->

                <?php endforeach; ?>

                <!------------------------------------------------------------------------------------------------->

            <?php else: ?>

                <!------------------------------------------------------------------------------------------------->

                <div class="col-span-full flex flex-col items-center justify-center py-32 bg-slate-800/80 backdrop-blur-md rounded-[3rem] border border-red-500/20 shadow-xl">
                   
                    <div class="w-24 h-24 bg-red-500/10 text-red-400 rounded-full flex items-center justify-center text-4xl mb-6 border border-red-500/20">
                        
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        
                    </div>
                    
                    <h3 class="text-2xl font-bold text-white mb-2">Dataset Not Found</h3>

                    <p class="text-slate-400 text-lg">We couldn't locate the LV2.xml file or it contains invalid data.</p>
                
                </div>

                <!------------------------------------------------------------------------------------------------->

            <?php endif; ?>

            <!------------------------------------------------------------------------------------------------->

        </div>

        <!------------------------------------------------------------------------------------------------->

    </div>

    <!------------------------------------------------------------------------------------------------->
    
</body>

<!------------------------------------------------------------------------------------------------->

</html>

<!------------------------------------------------------------------------------------------------->
