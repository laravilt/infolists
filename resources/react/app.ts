/**
 * Infolists plugin (React twin of app.js).
 *
 * The Vue plugin registers no global components (Schema/ActionButton import the entries directly);
 * `register()` keeps its only side effect, the install log.
 */
import InfoList from './components/InfoList';
import BadgeEntry from './components/entries/BadgeEntry';
import CodeEntry from './components/entries/CodeEntry';
import ColorEntry from './components/entries/ColorEntry';
import IconEntry from './components/entries/IconEntry';
import ImageEntry from './components/entries/ImageEntry';
import KeyValueEntry from './components/entries/KeyValueEntry';
import RepeatableEntry from './components/entries/RepeatableEntry';
import TextEntry from './components/entries/TextEntry';

export { InfoList, BadgeEntry, CodeEntry, ColorEntry, IconEntry, ImageEntry, KeyValueEntry, RepeatableEntry, TextEntry };

export default {
    register(options: Record<string, any> = {}): void {
        // Plugin installation logic
        console.log('Infolists plugin installed', options);
    },
};
