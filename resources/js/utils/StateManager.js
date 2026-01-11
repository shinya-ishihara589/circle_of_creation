/**
 * 状態を管理するクラス
 * - デフォルト：READY
 * - 状態一覧
 * -- READY
 * -- PLAYING
 * -- CLEARED
 * -- GAME_OVER
 */
export class StateManager {
    #state = StateManager.STATES.READY;

    static GAME_STATES = {
        READY: 'READY',
        START: 'START',
        PLAYING: 'PLAYING',
        CLEARED: 'CLEARED',
        GAME_OVER: 'GAME_OVER',
    };

    /**
     * 状態を更新する
     * @param {*} state 
     */
    update(state) {
        if (!Object.values(StateManager.STATES).includes(state)) {
            throw new Error(`Invalid state: ${state}`);
        }
        this.#state = state;
    }

    getState() {
        return this.#state;
    }
}
