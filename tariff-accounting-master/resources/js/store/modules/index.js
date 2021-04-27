import camelCase from 'lodash/camelCase'

const requireModule = require.context('.', true, /^\.\/[a-zA-Z]+\/[a-zA-Z]+\.js$/)
var modules = {};

requireModule.keys().forEach(fileName => {
    const files = ['actions.js', 'mutations.js', 'getters.js', 'state.js', 'rules.js', 'model.js', 'filter.js', 'columns.js']; // contains files

    if (files.indexOf(fileName.substr(fileName.lastIndexOf("/") + 1)) > -1) return;

    const moduleName = camelCase(
        fileName.replace(/(\.\/[a-zA-Z]+\/|\.js)/g, '')
    );

    modules[moduleName] = {
        namespaced: true,
        ...requireModule(fileName).default
    }
});

export default modules