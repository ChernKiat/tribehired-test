// import WaifuSocket from './index.js';
import fs from 'fs/promises';
import WaifuSocket from 'waifusocket';
// let fs = require('fs/promises');
// let WaifuSocket = require('waifusocket');

(async () => {
    const ws = await new WaifuSocket().login('SFMyNTY.g2gDbQAAACBYkXCBc7hS676XBwDeoCVxSBR0nJkhI45vu583VwcgT24GAERvH6x_AWIATxoA.I6Yh1lNFZRoJw5Nofsq0C2Qndqwdl10VjVFTiJ5GpJQ');

    ws.once('ready', async () => {
      const grid = await ws.genGrid();
      console.log(grid.length);
      const big = await ws.genBig(grid[0].seeds);
      await fs.writeFile('./image.png', big.image);
      ws.close();
    });
})().catch(e => {
    console.log(e);
});
