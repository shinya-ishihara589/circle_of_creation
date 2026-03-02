/**
 * ゲームタイマーのクラス
 */
export class GameTimer {
    #startTime; // ゲームスタート時の時間
    #endTime; // ゲーム終了時の時間

    /**
     * 
     */
    startGame() {
        this.#startTime = new Date();
    }

    endGame() {
        this.#endTime = new Date();
    }

    restartGame() {
        this.#startTime = new Date();
        this.#endTime = null;
    }

    /**
     * クリアタイムを取得する
     */
    get clearTime() {
        return this.#endTime - this.#startTime;
    }
}
