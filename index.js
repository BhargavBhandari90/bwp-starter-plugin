#!/usr/bin/env node

import fs from 'fs-extra';
import path from 'path';
import { fileURLToPath } from 'url';
import chalk from 'chalk';
import inquirer from 'inquirer';

// Get __dirname equivalent in ES Modules
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);

// Function to recursively get all files in a directory
function getAllFiles(dir) {
    const files = fs.readdirSync(dir);
    let fileList = [];

    for (const file of files) {
        const filePath = path.join(dir, file);
        const stat = fs.statSync(filePath);

        if (stat.isDirectory()) {
            // Recursively get files in subdirectories
            fileList = fileList.concat(getAllFiles(filePath));
        } else if (stat.isFile()) {
            // Add file to the list
            fileList.push(filePath);
        }
    }

    return fileList;
}

// Function to replace placeholders in files
function replacePlaceholders(filePath, pluginName, pluginData) {
    if (!pluginData) {
        return
    }
    let content = fs.readFileSync(filePath, 'utf8');
    content = content.replace(/PLUGIN_NAME/g, pluginName);
    content = content.replace(/PLUGIN_SLUG/g, pluginName.toLowerCase().replace(/\s+/g, '-'));
    content = content.replace(/PLUGIN_DESCRIPTION/g, pluginData?.description);
    content = content.replace(/PLUGIN_VERSION/g, pluginData?.version);
    content = content.replace(/PLUGIN_FPREFIX/g, pluginData?.function_prefix);
    content = content.replace(/PLUGIN_CPREFIX/g, pluginData?.constant_prefix);
    content = content.replace(/PLUGIN_AUTHOR/g, pluginData?.author);
    content = content.replace(/PLUGIN_PACKAGE/g, pluginData?.package_name);
    content = content.replace(/PLUGIN_CLASS/g, pluginData?.class_prefix);
    fs.writeFileSync(filePath, content);
}

// Function to generate the plugin
async function generatePlugin(pluginName, targetDir, pluginData) {
    try {
        const templateDir = path.join(__dirname, 'template', 'plugin-name');
        const destinationDir = path.join(targetDir, pluginName.toLowerCase().replace(/\s+/g, '-'));

        // Copy template to destination
        await fs.copy(templateDir, destinationDir);

        // Rename files and replace placeholders
        const filesToRename = [
            { old: 'plugin-name.php', new: `${pluginName.toLowerCase().replace(/\s+/g, '-')}.php` },
        ];

        filesToRename.forEach((file) => {
            const oldPath = path.join(destinationDir, file.old);
            const newPath = path.join(destinationDir, file.new);
            fs.renameSync(oldPath, newPath);
            replacePlaceholders(newPath, pluginName, pluginData);
        });

        // Replace placeholders in all files
        // const filesToProcess = [
        //     `${pluginName.toLowerCase().replace(/\s+/g, '-')}.php`,
        //     'readme.txt',
        //     `package.json`,
        // ];

        // filesToProcess.forEach((file) => {
        //     const filePath = path.join(destinationDir, file);
        //     replacePlaceholders(filePath, pluginName, pluginData);
        // });

        // Get all files in the destination directory
        const filesToProcess = getAllFiles(destinationDir);

        // Replace placeholders in all files
        for (const file of filesToProcess) {
            // replacePlaceholders(file, replacements);
            replacePlaceholders(file, pluginName, pluginData);
        }

        console.log(chalk.green(`✅ WordPress plugin "${pluginName}" created successfully in ${destinationDir}`));
    } catch (err) {
        console.error(chalk.red('❌ Error generating plugin:', err));
    }
}

// Generate the plugin
// generatePlugin(pluginName, process.cwd());

// Prompt user for input
async function promptUser() {
    const answers = await inquirer.prompt([
        {
            type: 'input',
            name: 'description',
            message: 'Enter the plugin description:',
            default: 'A brief description of the plugin.',
        },
        {
            type: 'input',
            name: 'author',
            message: 'Enter the plugin author:',
            default: 'Your Name',
        },
        {
            type: 'input',
            name: 'version',
            message: 'Enter the plugin version:',
            default: '1.0.0',
        },
        {
            type: 'input',
            name: 'function_prefix',
            message: 'Enter the funciton prefix for the plugin:',
            default: 'myplugin',
        },
        {
            type: 'input',
            name: 'constant_prefix',
            message: 'Enter the constants prefix for the plugin:',
            default: 'MYPLUGIN',
        },
        {
            type: 'input',
            name: 'class_prefix',
            message: 'Enter the class name prefix for the plugin:',
            default: 'My_Plugin',
        },
        {
            type: 'input',
            name: 'package_name',
            message: 'Enter the package name for the plugin:',
            default: 'My_Plugin',
        },
    ]);

    return answers;
}

// Main function
async function main(pluginName) {
    try {
        // Get user input
        const { description, author, version, function_prefix, constant_prefix, package_name, class_prefix } = await promptUser();

        // Generate the plugin
        await generatePlugin(pluginName, process.cwd(), { description, author, version, function_prefix, constant_prefix, package_name, class_prefix });
    } catch (err) {
        console.error(chalk.red('❌ Error:', err));
    }
}

// Get plugin name from command line arguments
const pluginName = process.argv[2];
if (!pluginName) {
    console.error(chalk.red('❌ Please provide a plugin name. Example: npx create-wordpress-plugin MyPlugin'));
    process.exit(1);
}

// Run the script
main(pluginName);