

SELECT hash, count(*) as c
FROM transactions 
WHERE hash IN (
    SELECT hash FROM transactions 
    WHERE "to" = '0x7be8076f4ea4a4ad08075c2508e481d6c946d12b' 
    OR "from" = '0x7be8076f4ea4a4ad08075c2508e481d6c946d12b'
)
GROUP BY hash
HAVING count(*) = 1
ORDER BY c ASC;


ALTER TABLE transactions
  ADD reprocessed integer;

  