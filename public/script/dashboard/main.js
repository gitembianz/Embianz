// Import Components .
import { aside } from "./components/aside.js";
import { opener } from "./components/opener.js";
import { tabs } from "./components/tabs.js";
import { dropdown } from "./components/dropdown.js";


/// Navigation (Header)
aside('menu', 'menu__open', 'menu__close', 'menu__backdrop');
aside('profile', 'profile__open', 'profile__close', 'profile__backdrop');
aside('search', 'search__open', 'search__close', 'search__backdrop');
aside('notify', 'notify__open', 'notify__close', 'notify__backdrop');

/// Navigation (Body)
opener('leftbar', 'toggle__leftbar', false);

/// Navigation (View Table)
aside('sort', 'sort__open', 'sort__close', 'sort__backdrop');
aside('visi', 'visi__open', 'visi__close', 'visi__backdrop');

/// Tabs (Show Table)
tabs('detailsButton','relatedButton','detailsContent','relatedContent');

// All Dropdowns
dropdown();
