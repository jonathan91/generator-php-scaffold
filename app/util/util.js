
const path = require('path');

module.exports = {
    rewriteFile,
}
function rewriteFile(args, generator) {
    var fs = require('fs');
    args.path = args.path || process.cwd();
    const fullPath = path.join(args.path, args.file);

    args.haystack = generator.fs.read(fullPath);
    const body = rewrite(args);
    
    generator.fs.write(fullPath, body);
}

function escapeRegExp(str) {
    return str.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, '\\$&'); // eslint-disable-line
}

function rewrite(args) {
    // check if splicable is already in the body text
    const re = new RegExp(args.splicable.map(line => `"\\s*${escapeRegExp(line)}`).join('\n'));

    if (re.test(args.haystack)) {
        return args.haystack;
    }

    const lines = args.haystack.split('\n');

    let otherwiseLineIndex = -1;
    lines.forEach((line, i) => {
        if (line.includes(args.needle)) {
            otherwiseLineIndex = i;
        }
    });

    let spaces = 0;
    if (lines[otherwiseLineIndex] !== undefined){
        while (lines[otherwiseLineIndex].charAt(spaces) === ' ') {
            spaces += 1;
        }
    }

    let spaceStr = '';

    while ((spaces -= 1) >= 0) { // eslint-disable-line no-cond-assign
        spaceStr += ' ';
    }

    lines.splice(otherwiseLineIndex, 0, args.splicable.map(line => spaceStr + line).join('\n'));
    return lines.join('\n');
}
