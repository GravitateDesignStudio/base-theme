const presets = [
	'@babel/preset-env'
];

const plugins = [
	'@babel/plugin-transform-runtime',
	'@babel/plugin-syntax-dynamic-import',
	'@babel/plugin-proposal-class-properties',
	'@babel/plugin-proposal-object-rest-spread',
	'@babel/plugin-proposal-nullish-coalescing-operator',
	'@babel/plugin-proposal-optional-chaining'
];

module.exports = { presets, plugins };
