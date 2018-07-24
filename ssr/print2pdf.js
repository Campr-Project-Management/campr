const htmlPdf = require('html-pdf-chrome');

const options = {
    port: 9222,
    completionTrigger: new htmlPdf.CompletionTrigger.Timer(10000),
};

const url = process.argv[2];
const file = process.argv[3];

htmlPdf.create(url, options).then((pdf) => {
    pdf.toFile(file);
});
