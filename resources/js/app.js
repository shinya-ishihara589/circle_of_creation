import './bootstrap';
import { CanvasRenderer } from './utils/CanvasRenderer.js';
import { GameTimer } from './utils/GameTimer.js';
import { InputManager } from './utils/InputManager.js';
import { StateManager } from './utils/StateManager.js';
import { NumberTouchGameManager } from './utils/NumberTouchGameManager.js';

window.CanvasRenderer = CanvasRenderer;
window.GameTimer = GameTimer;
window.InputManager = InputManager;
window.StateManager = StateManager;
window.NumberTouchGameManager = NumberTouchGameManager;
