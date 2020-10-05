--insert into land(LocalAuthorityID,LRN,PlotNo,RatesPayable,Balance,LastBillNumber,laifomsUPN,LaifomsOwner)
SELECT distinct p.LocalAuthorityID, p.BlockLRNumber lRN,p.PlotNumber PlotNo,P.LandRates,p.CurrentBalance,LastBillNumber,p.UPN,cs.CustomerSupplierName
	

  FROM [LAIFOMS-m].dbo.[Property]  p join [LAIFOMS-m].dbo.CustomerSupplier cs on p.CustomerSupplierID=cs.CustomerSupplierID
  where  p.LastBillYear='2016' -- and p.UPN='401-467'---and p.BlockLRNumber='15'
  order by p.BlockLRNumber, p.PlotNumber

