-- Query for existences of a product in a warehouse
SELECT
    p.product_id,
    CONCAT (p.code, ' - ', p.name) AS product,
    w.warehouse_id,
    CONCAT (w.code, ' - ', w.name) AS warehouse,
    SUM(
        CASE
            WHEN d.intended_for = 'I' THEN it.amount
            ELSE 0
        END
    ) AS amountInput,
    SUM(
        CASE
            WHEN d.intended_for = 'O' THEN it.amount
            ELSE 0
        END
    ) AS amountOutput
FROM
    transaction_item it
    LEFT JOIN transaction t ON it.transaction_id = t.transaction_id
    AND t.status = 'A'
    LEFT JOIN document d ON t.document_id = d.document_id
    AND d.status = 'A'
    LEFT JOIN product p ON it.product_id = p.product_id
    AND p.status = 'A'
    LEFT JOIN warehouse w ON it.warehouse_id = w.warehouse_id
    AND w.status = 'A'
WHERE
    it.company_id = 1
    -- AND p.product_id = 1 
    -- AND w.warehouse_id is null 
    -- AND w.warehouse_id = 1 
    AND t.creation_date <= '2025-02-19'
GROUP BY
    p.product_id,
    w.warehouse_id;

-- Query for starting the kardex of a product in a warehouse
SELECT
    d.document_id,
    d.intended_for,
    d.has_other_transaction,
    t.transaction_id,
    CONCAT (d.code, ' - ', t.num_transaction) AS transaction,
    t.creation_date,
    t.linked_transaction_id,
    CASE
        WHEN d.intended_for = 'I' THEN it.transaction_item_id
    END as transaction_item_id_input,
    CASE
        WHEN d.intended_for = 'I' THEN it.amount
    END as amount_input,
    CASE
        WHEN d.intended_for = 'I' THEN it.unit_value
    END as unit_value_input,
    CASE
        WHEN d.intended_for = 'O' THEN it.transaction_item_id
    END as transaction_item_id_output,
    CASE
        WHEN d.intended_for = 'O' THEN it.amount
    END as amount_output,
    CASE
        WHEN d.intended_for = 'O' THEN it.unit_value
    END as unit_value_output
FROM
    transaction_item it
    LEFT JOIN transaction t ON it.transaction_id = t.transaction_id
    AND t.status = 'A'
    LEFT JOIN document d ON t.document_id = d.document_id
    AND d.status = 'A'
    LEFT JOIN product p ON it.product_id = p.product_id
    AND p.status = 'A'
    LEFT JOIN warehouse w ON it.warehouse_id = w.warehouse_id
    AND w.status = 'A'
WHERE
    it.company_id = 1
    AND p.product_id = 1
    -- AND w.warehouse_id IS NULL 
    AND w.warehouse_id = 3
    AND t.creation_date <= '2025-02-20'
ORDER BY
    t.creation_date,
    t.created_at;