(async function(){
    // Setup the module
    const waifulabs = require('waifulabs');

    // Get some pretty waifus
    const waifus = await waifulabs.generateWaifus();

    // Extract one waifu
    const waifu = waifus[0];

    // Extract the image of the waifu
    const imageData = waifu.image;

    // Turn the Base64 image into a Buffer
    const image = Buffer.from(imageData, 'base64');

    // Use FS module and write the image to a file
    const fs = require('fs');
    fs.writeFile('waifu.png', image, console.error);

    /* Done! */
})()
