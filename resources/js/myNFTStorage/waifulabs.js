import WaifuSocket from './index.js';
import fs from 'fs/promises';

const ws = await new WaifuSocket().login();

ws.once('ready', async () => {
  const grid = await ws.genGrid();
  console.log(grid.length);
  const big = await ws.genBig(grid[0].seeds);
  await fs.writeFile('./image.png', big.image);
  ws.close();
});
