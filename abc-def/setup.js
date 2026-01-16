// setup.js
const fs = require('fs').promises;
const path = require('path');
const readline = require('readline');

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

const question = (prompt) => new Promise(resolve => rl.question(prompt, resolve));

const config = {
  replacements: [
    { find: 'abc-def', description: 'Plugin folder string', key: 'folderSlug' },
    { find: 'ab-cd', description: 'Short plugin string', key: 'shortSlug' },
    { find: 'MMM', description: 'Plugin AGAL/NAMESPACE', key: 'namespace' },
    { find: 'NNN', description: 'Plugin name', key: 'name' },
    { find: 'PPP', description: 'Plugin description', key: 'description' }
  ]
};

async function replaceInFile(filePath, replacements) {
  let content = await fs.readFile(filePath, 'utf8');
  Object.entries(replacements).forEach(([find, replace]) => {
    content = content.replace(new RegExp(find, 'g'), replace);
  });
  await fs.writeFile(filePath, content);
}

async function renameFile(oldPath, replacements) {
  let newPath = oldPath;
  Object.entries(replacements).forEach(([find, replace]) => {
    newPath = newPath.replace(new RegExp(find, 'g'), replace);
  });
  if (newPath !== oldPath) {
    await fs.rename(oldPath, newPath);
  }
  return newPath;
}

async function getAllFiles(dir, fileList = []) {
  const files = await fs.readdir(dir, { withFileTypes: true });
  for (const file of files) {
    const filePath = path.join(dir, file.name);
    if (file.isDirectory() && file.name !== 'node_modules' && !file.name.startsWith('.')) {
      await getAllFiles(filePath, fileList);
    } else if (file.isFile() && file.name !== 'setup.js' && file.name !== 'gulpfile.js') {
      fileList.push(filePath);
    }
  }
  return fileList;
}

async function setup() {
  console.log('🚀 WordPress Plugin Setup\n');
  
  const answers = {};
  for (const item of config.replacements) {
    answers[item.find] = await question(`${item.description}: `);
  }
  
  console.log('\n📝 Preview:');
  config.replacements.forEach(item => {
    console.log(`  ${item.find} → ${answers[item.find]}`);
  });
  
  const confirm = await question('\nProceed with replacement? (y/n): ');
  if (confirm.toLowerCase() !== 'y') {
    console.log('❌ Cancelled');
    rl.close();
    return;
  }
  
  const files = await getAllFiles('.');
  console.log(`\n🔄 Processing ${files.length} files...`);
  
  for (const file of files) {
    await replaceInFile(file, answers);
    await renameFile(file, answers);
  }
  
  console.log('✅ Setup complete!');
  rl.close();
}

setup().catch(err => {
  console.error('❌ Error:', err);
  rl.close();
  process.exit(1);
});