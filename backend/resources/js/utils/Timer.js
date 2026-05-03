/**
 * ゲームタイマーを管理するクラス
 */
export class Timer {
    #startTime = null;
    #endTime = null;
    #timerInterval = null;

    /**
     * タイマーを開始する
     * @param {Function} callback 毎秒（毎フレーム）実行したい処理
     */
    start(callback) {
        this.#startTime = Date.now();
        this.#endTime = null;

        const update = () => {
            if (!this.#startTime || this.#endTime) {
                return;
            }

            if (callback) {
                callback(this.formattedTime);
            }
            this.#timerInterval = requestAnimationFrame(update);
        };

        this.#timerInterval = requestAnimationFrame(update);
    }

    /**
     * タイマーを停止する
     */
    stop() {
        this.#endTime = Date.now();
        cancelAnimationFrame(this.#timerInterval);
    }

    /**
     * リセット
     */
    reset() {
        this.stop();
        this.#startTime = null;
        this.#endTime = null;
    }

    /**
     * 現在の経過時間を「00:00.00」の形式で取得する（読み取り専用）
     */
    get formattedTime() {
        if (!this.#startTime) return "0:00.00";

        const now = this.#endTime || Date.now();
        const diffMs = now - this.#startTime;

        const m = Math.floor(diffMs / 60000);
        const s = Math.floor((diffMs % 60000) / 1000);
        const ms = Math.floor((diffMs % 1000) / 10);

        const strM = String(m).padStart(1, '0');
        const strS = String(s).padStart(2, '0');
        const strMs = String(ms).padStart(2, '0');

        return `${strM}:${strS}.${strMs}`;
    }
}
