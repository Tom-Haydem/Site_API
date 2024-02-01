<?php
    // S'il existe des informations sur l'utilisateur 
    if (isset($userInfo)):?>
    <div class="absolute top-0 right-0 m-5 p-4 bg-gray-800 rounded-lg shadow-lg border border-gray-700">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-white"><?= htmlspecialchars($userInfo['display_name']) ?></h1>
                <p class="text-green-400"><?= $userInfo['product'] === 'premium' ? 'Compte Premium' : 'Compte Freemium' ?></p>
                <h2> <?= $userInfo['followers']['total'] ?> followers</h2>
            </div>
            <img class="w-32 h-32 rounded-full border-4 object-cover hover:border-green-500 cursor-pointer" src="<?= htmlspecialchars($userInfo['images'][0]['url']) ?>" alt="<?= htmlspecialchars($userInfo['display_name']) ?>" onclick="window.open('<?= htmlspecialchars($userInfo['external_urls']['spotify']) ?>', '_blank')">                        </div>
            
    </div>
<?php endif; ?>
