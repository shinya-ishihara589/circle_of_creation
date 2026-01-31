/**
 * ステータス
 * READY：スタートボタンの表示
 * PLAYING：パネル表示
 * CLEARED：クリアタイムの表示：実施
 * GAME_OVER：ゲームオーバーとリスタートボタンの表示
 */

/**
 * パネルのクラス
 * クリックの制御
 * 最小値の場合はTrue、それ以外の場合はFalse
 * 
 * 課題：setter getterを使用する
 *  -    set num(), get num ()など
 */
export class NumberTouchGameManager {
    #num = 0;
    #panelWidth = 100;
    #panelHeight = 100;
    #numOfSides = 5;
    #numOfPanel;
    #panelNums;

    #startTime;
    #endTime;
    #state = 'READY';

    #GAME_STATES = {
        READY: 'READY',
        START: 'START',
        PLAYING: 'PLAYING',
        CLEARED: 'CLEARED',
        GAME_OVER: 'GAME_OVER',
    }

    #START_PROPERTY = {
        LAYOUT: 'START',
    }

    constructor() {
        this.#numOfPanel = this.#numOfSides * this.#numOfSides;

        this.#panelNums = Array.from({
            length: this.#numOfPanel
        }, (_, i) => i);

        for (let i = this.#panelNums.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * i);
            [this.#panelNums[i], this.#panelNums[j]] = [this.#panelNums[j], this.#panelNums[i]];
        }
    }

    pushStart() {
        this.#state = 'START';
        this.#startTime = new Date();
    }

    /**
     * 当たり判定
     * @return Integer
     */
    judge(clickPosX, clickPosY) {
        let panelNum = this.getNumForPos(clickPosX, clickPosY);
        let isCorrect = false;
        // 1.
        if (this.#num === this.#panelNums[panelNum]) {
            isCorrect = true;
        }

        if (this.#panelNums.length === this.#num + 1 && this.#num === this.#panelNums[panelNum]) {
            this.#endTime = new Date();
            this.#state = this.#GAME_STATES.CLEARED;
        }
        return isCorrect;
    }

    /**
     * 列数×辺数+行数=パネル番号
     * パネル番号の配列番号を取得する
     */
    getNumForPos(posX, posY) {
        // X軸からXのパネル列を取得する
        // バグあり：ギリギリで取得すると0と6が存在する
        let panelCol = Math.floor(posX / 100);
        let panelRow = Math.floor(Math.floor(posY / 100) % this.#numOfSides);
        // 計算方法を修正する
        let num = panelRow * this.#numOfSides + panelCol;
        return num;
    }

    getPos() {
        const arrKey = this.#panelNums.indexOf(this.#num);
        let x = (arrKey % this.#numOfSides) * this.#panelWidth;
        let y = Math.floor(arrKey / this.#numOfSides) * this.#panelHeight;
        return {
            x: x,
            y: y,
        }
    }
    numCount() {
        this.#num++;
    }

    get num() {
        return this.#num;
    }

    get panelNums() {
        return this.#panelNums;
    }

    get width() {
        return this.#panelWidth;
    }

    get height() {
        return this.#panelHeight;
    }

    get state() {
        return this.#state;
    }

    get numOfSides() {
        return this.#numOfSides;
    }

    get panelRect() {
        return {
            x: x,
            y: x,
            width: this.#panelWidth,
            height: this.#panelHeight,
        }
    }

    get clearTime() {
        let ms = this.#endTime - this.#startTime
        const totalSeconds = ms / 1000;
        const minutes = Math.floor(totalSeconds / 60);
        const seconds = Math.floor(totalSeconds % 60);
        const centiseconds = Math.floor((totalSeconds - Math.floor(totalSeconds)) * 100);

        // 秒と小数点以下はゼロ埋め
        const s = String(seconds).padStart(2, '0');
        const cs = String(centiseconds).padStart(2, '0');

        return `${minutes}:${s}.${cs}`;
    }

    set state(state) {
        this.#state = state;
    }
}
