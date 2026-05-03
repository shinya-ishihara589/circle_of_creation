/**
 * ナンバータッチゲームのロジック管理クラス
 */
export class NumberTouchGameManager {
    #currentNum = 0;        // 次に押すべき数字 (0から開始)
    #numOfSides = 5;        // 1辺のパネル数
    #panels = [];           // シャッフルされた数字配列
    #state = 'READY';

    #GAME_STATES = {
        READY: 'READY',
        START: 'START',
        PLAYING: 'PLAYING',
        CLEARED: 'CLEARED',
        GAME_OVER: 'GAME_OVER',
    };

    constructor(numOfSides = 5) {
        if (numOfSides < 2) {
            numOfSides = 2;
        } else if (numOfSides > 5) {
            numOfSides = 5;
        }
        this.#numOfSides = numOfSides;
        this.initGame();
    }

    /**
     * ゲームの初期化（配列作成とシャッフル）
     */
    initGame() {
        this.#currentNum = 0;
        this.#state = this.#GAME_STATES.READY;
        const totalPanels = this.#numOfSides * this.#numOfSides;

        // 0 ～ (total-1) の配列を作成
        this.#panels = Array.from({ length: totalPanels }, (_, i) => i);

        // フィッシャー–イェーツのシャッフル
        for (let i = this.#panels.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [this.#panels[i], this.#panels[j]] = [this.#panels[j], this.#panels[i]];
        }
    }

    /**
     * 正解判定（DOMから受け取った数字で判定）
     * @param {number} tappedNum 押されたパネルの数字
     * @return {boolean}
     */
    checkAnswer(tappedNum) {
        if (this.#state !== this.#GAME_STATES.PLAYING) return false;

        // 次に押すべき数字と一致しているか
        if (tappedNum === this.#currentNum) {
            this.#currentNum++;

            // 全て押し終えたか判定
            if (this.#currentNum === this.#panels.length) {
                this.#state = this.#GAME_STATES.CLEARED;
            }
            return true;
        }
        return false;
    }

    /* --- Getter / Setter --- */

    get state() { return this.#state; }
    set state(val) {
        if (this.#GAME_STATES[val]) {
            this.#state = val;
        }
    }

    get panels() { return this.#panels; }
    get currentNum() { return this.#currentNum; }
    get numOfSides() { return this.#numOfSides; }

    // クリアしたかどうか
    get isCleared() { return this.#state === this.#GAME_STATES.CLEARED; }
}
